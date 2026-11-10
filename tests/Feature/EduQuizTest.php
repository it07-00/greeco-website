<?php

namespace Tests\Feature;

use App\Models\EduQuiz\Attempt;
use App\Models\EduQuiz\Question;
use App\Models\EduQuiz\Quiz;
use App\Models\EduQuiz\Room;
use App\Models\User;
use App\Services\EduQuiz\Play;
use App\Services\EduQuiz\Privacy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class EduQuizTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_build_a_quiz_and_publish_a_room_with_a_frozen_question_snapshot(): void
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);
        foreach (['index', 'questions', 'questions.edit', 'quizzes', 'quizzes.edit', 'courses', 'rooms', 'participants', 'results', 'settings'] as $page) {
            $this->get(route('eduquiz.staff.'.$page))->assertOk();
        }

        $question = $this->question('Kiểm kê khí nhà kính');
        $other = $this->question('Câu số hai');
        $this->post(route('eduquiz.staff.quizzes.save'), [
            'title' => 'Khóa kiểm kê',
            'questions' => [$other->id, $question->id],
        ])->assertRedirect();
        $quiz = Quiz::sole();
        $this->get(route('eduquiz.staff.quizzes.edit', $quiz))->assertOk();
        $this->post(route('eduquiz.staff.rooms.create'), ['quiz_id' => $quiz->id, 'mode' => 'live'])->assertRedirect();
        $room = Room::sole();
        $this->get(route('eduquiz.staff.rooms.show', $room))->assertOk()->assertSee($room->pin);
        $this->get(route('eduquiz.staff.rooms.qr', $room))->assertOk()->assertHeader('Content-Type', 'image/svg+xml')->assertSee('<svg', false);
        $this->post(route('eduquiz.staff.questions.save', $question), $this->questionData('Nội dung đã cập nhật'))->assertRedirect();
        $this->assertDatabaseCount('eduquiz_question_revisions', 3);
        $this->assertSame(['Câu số hai', 'Kiểm kê khí nhà kính'], array_column($room->questions, 'prompt'));
        $this->post(route('eduquiz.staff.questions.copy', $other))->assertRedirect();
        $this->assertDatabaseCount('eduquiz_questions', 3);
    }

    public function test_live_controls_protect_answers_and_grade_once_using_the_server_clock(): void
    {
        $admin = User::factory()->create();
        $room = $this->room($admin);
        $room->update(['questions' => [$room->questions[0], [...$room->questions[0], 'prompt' => 'Câu tiếp theo']]]);
        $this->get(route('eduquiz.room', $room->pin))->assertOk()->assertSee($room->pin);
        $this->getJson(route('eduquiz.state', $room->pin))->assertForbidden();
        $this->postJson(route('eduquiz.staff.rooms.control', $room), ['action' => 'start', 'version' => 0])->assertUnauthorized();

        $this->join($room);
        $this->get(route('eduquiz.room', $room->pin))->assertOk()->assertSee('EDUQUIZ LIVE');
        $this->getJson(route('eduquiz.state', $room->pin))->assertJsonPath('phase', 'lobby')->assertJsonPath('questions', []);
        $this->control($admin, $room, 'start', 0)->assertNoContent();
        $this->control($admin, $room, 'start', 0)->assertConflict();
        $this->getJson(route('eduquiz.state', $room->pin))->assertJsonPath('questions.0.correct', null)
            ->assertJsonPath('questions.0.explanation', null)->assertDontSee('viên@example.test');
        $this->control($admin, $room, 'pause', 1)->assertNoContent();
        $this->postJson(route('eduquiz.answer', $room->pin), ['position' => 0, 'choices' => [1]])->assertConflict();
        $this->travel(40)->seconds();
        $this->getJson(route('eduquiz.state', $room->pin))->assertJsonPath('phase', 'paused');
        $this->control($admin, $room, 'resume', 2)->assertNoContent();

        $this->postJson(route('eduquiz.answer', $room->pin), ['position' => 0, 'choices' => [4]])->assertUnprocessable();
        $this->postJson(route('eduquiz.answer', $room->pin), ['position' => 0, 'choices' => [1]])->assertNoContent();
        $this->postJson(route('eduquiz.answer', $room->pin), ['position' => 0, 'choices' => [1]])->assertNoContent();
        $this->postJson(route('eduquiz.answer', $room->pin), ['position' => 0, 'choices' => [0]])->assertConflict();
        $this->getJson(route('eduquiz.state', $room->pin))->assertJsonPath('questions.0.choices', [1]);
        $this->assertDatabaseCount('eduquiz_answers', 1);

        $this->travel(45)->seconds();
        $this->getJson(route('eduquiz.state', $room->pin))->assertJsonPath('phase', 'closed');
        $this->control($admin, $room, 'reveal', 3)->assertNoContent();
        $this->getJson(route('eduquiz.state', $room->pin))->assertJsonPath('questions.0.correct', [1]);
        $this->actingAs($admin)->getJson(route('eduquiz.staff.rooms.state', $room))->assertOk()
            ->assertJsonPath('counts', [0, 1])->assertJsonPath('leaders.0.score', 1);
        Auth::forgetGuards();
        $this->control($admin, $room, 'next', 4)->assertNoContent();
        $this->getJson(route('eduquiz.state', $room->pin))->assertJsonPath('questions.0.prompt', 'Câu tiếp theo');
        $this->postJson(route('eduquiz.answer', $room->pin), ['position' => 0, 'choices' => [1]])->assertConflict();
        $this->control($admin, $room, 'close', 5)->assertNoContent();
        $this->control($admin, $room, 'finish', 6)->assertNoContent();
        $this->getJson(route('eduquiz.state', $room->pin))->assertJsonPath('result.score', 1)->assertJsonPath('result.total', 2);
        $this->assertDatabaseCount('eduquiz_results', 1);
    }

    public function test_deleting_a_quiz_keeps_its_room_and_result_and_removes_it_from_new_room_choices(): void
    {
        $admin = User::factory()->create();
        $room = $this->room($admin, 'self');
        $quiz = Quiz::findOrFail($room->quiz_id);
        $this->actingAs($admin);
        $question = $this->question($room->questions[0]['prompt']);
        $quiz->questions()->attach($question, ['position' => 0]);
        $this->join($room);
        $this->postJson(route('eduquiz.submit', $room->pin))->assertNoContent();

        $this->actingAs($admin)->get(route('eduquiz.staff.quizzes'))->assertOk()->assertSee('Xóa bộ đề');
        $this->get(route('eduquiz.staff.rooms'))->assertOk()->assertSee('<option value="'.$quiz->id.'"', false);
        $this->delete(route('eduquiz.staff.quizzes.delete', $quiz))->assertRedirect(route('eduquiz.staff.quizzes'));

        $this->get(route('eduquiz.staff.quizzes'))->assertOk()->assertDontSee($quiz->title);
        $this->get(route('eduquiz.staff.questions'))->assertOk()->assertSee($question->prompt);
        $this->get(route('eduquiz.staff.quizzes.edit', $quiz))->assertNotFound();
        $this->get(route('eduquiz.staff.rooms'))->assertOk()->assertDontSee('<option value="'.$quiz->id.'"', false);
        $this->post(route('eduquiz.staff.rooms.create'), ['quiz_id' => $quiz->id, 'mode' => 'live'])->assertNotFound();
        $this->get(route('eduquiz.staff.rooms.show', $room))->assertOk()->assertSee($room->pin);
        $this->get(route('eduquiz.staff.results.detail', Attempt::sole()))->assertOk();
        $this->assertDatabaseCount('eduquiz_results', 1);
    }

    public function test_deleting_a_used_question_requires_ownership_and_keeps_existing_room_content(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        DB::table('eduquiz_instructors')->insert([['user_id' => $owner->id], ['user_id' => $other->id]]);
        $this->actingAs($owner);
        $question = $this->question('Câu hỏi cần xóa');
        $room = $this->room($owner, 'self', ['questions' => [$question->snapshot()]]);
        $quiz = Quiz::findOrFail($room->quiz_id);
        $quiz->questions()->attach($question, ['position' => 0]);

        $this->actingAs($other)->delete(route('eduquiz.staff.questions.delete', $question))->assertForbidden();
        $this->delete(route('eduquiz.staff.quizzes.delete', $quiz))->assertForbidden();
        $this->actingAs($owner)->get(route('eduquiz.staff.questions'))->assertOk()->assertSee('Xóa câu hỏi');
        $this->delete(route('eduquiz.staff.questions.delete', $question))->assertRedirect(route('eduquiz.staff.questions'));
        $this->get(route('eduquiz.staff.questions'))->assertOk()->assertDontSee($question->prompt);
        $this->get(route('eduquiz.staff.questions.edit', $question))->assertNotFound();
        $this->get(route('eduquiz.staff.quizzes.edit', $quiz))->assertOk()->assertDontSee($question->prompt);
        $this->get(route('eduquiz.staff.rooms'))->assertOk()->assertDontSee('<option value="'.$quiz->id.'"', false);
        $this->post(route('eduquiz.staff.rooms.create'), ['quiz_id' => $quiz->id, 'mode' => 'live'])->assertSessionHasErrors('quiz_id');
        $this->post(route('eduquiz.staff.quizzes.save'), ['title' => 'Đề mới', 'questions' => [$question->id]])
            ->assertSessionHasErrors('questions');
        $this->assertSame($question->prompt, $room->fresh()->questions[0]['prompt']);
    }

    public function test_deleting_all_library_items_ignores_filters_and_respects_staff_scope(): void
    {
        $admin = User::factory()->create();
        $instructor = User::factory()->create();
        DB::table('eduquiz_instructors')->insert(['user_id' => $instructor->id]);
        $this->actingAs($admin);
        $otherQuestion = $this->question('Câu hỏi quản trị viên');
        $otherQuiz = Quiz::create(['owner_id' => $admin->id, 'title' => 'Đề của quản trị viên']);
        $this->actingAs($instructor);
        $this->question('Câu hỏi tìm thấy');
        $this->question('Câu hỏi ngoài bộ lọc');
        $room = $this->room($instructor);
        Quiz::create(['owner_id' => $instructor->id, 'title' => 'Đề thứ hai']);

        $questions = route('eduquiz.staff.questions', ['q' => 'tìm thấy']);
        $this->get($questions)->assertOk()->assertSee('Xóa tất cả câu hỏi')->assertDontSee('Câu hỏi ngoài bộ lọc');
        $this->get(route('eduquiz.staff.quizzes'))->assertOk()->assertSee('Xóa tất cả bộ đề');
        $this->post(route('eduquiz.staff.quizzes.delete-all', ['course' => 999999]), ['_method' => 'DELETE'])
            ->assertRedirect(route('eduquiz.staff.quizzes'));
        $this->assertSame([$otherQuiz->id], Quiz::pluck('id')->all());
        $this->assertSame(3, Question::count());
        $this->from($questions)->post(route('eduquiz.staff.questions.delete-all', ['q' => 'tìm thấy']), ['_method' => 'DELETE'])
            ->assertRedirect(route('eduquiz.staff.questions'));
        $this->assertSame([$otherQuestion->id], Question::pluck('id')->all());
        $this->get(route('eduquiz.staff.rooms.show', $room))->assertOk()->assertSee($room->pin);

        $this->actingAs($admin)->delete(route('eduquiz.staff.questions.delete-all'))->assertRedirect();
        $this->delete(route('eduquiz.staff.quizzes.delete-all'))->assertRedirect();
        $this->assertSame(0, Question::count());
        $this->assertSame(0, Quiz::count());
    }

    public function test_contact_normalization_prevents_duplicate_participation_without_restoring_unverified_access(): void
    {
        $room = $this->room(User::factory()->create());
        $this->post(route('eduquiz.join.store'), $this->joinData($room, ['email' => null, 'phone' => '0917 297 338']))->assertRedirect();
        $this->assertDatabaseHas('eduquiz_participants', ['phone' => '+84917297338']);
        $this->post(route('eduquiz.join.store'), $this->joinData($room, ['phone' => '+84 917 297 338']))
            ->assertSessionHasErrors('email')->assertCookieMissing('eduquiz_'.$room->pin);
        $this->getJson(route('eduquiz.state', $room->pin))->assertForbidden();
        $this->assertDatabaseCount('eduquiz_participants', 1);
    }

    public function test_self_paced_attempts_autosave_and_finalize_after_timeout_with_a_limited_retry(): void
    {
        $room = $this->room(User::factory()->create(), 'self', [
            'attempt_limit' => 2,
            'questions' => [[
                'prompt' => 'Chọn hai phương án',
                'seconds' => 30, 'image' => null, 'explanation' => 'Lời giải',
                'options' => [
                    ['text' => 'A', 'correct' => true],
                    ['text' => 'B', 'correct' => false],
                    ['text' => 'C', 'correct' => true],
                ],
            ]],
        ]);
        $first = $this->join($room);
        $this->getJson(route('eduquiz.state', $room->pin))->assertJsonPath('questions.0.multiple', true)
            ->assertJsonPath('questions.0.correct', null);
        $this->postJson(route('eduquiz.answer', $room->pin), ['position' => 0, 'choices' => [2]])->assertNoContent();
        $this->postJson(route('eduquiz.answer', $room->pin), ['position' => 0, 'choices' => [2, 0]])->assertNoContent();
        $this->getJson(route('eduquiz.state', $room->pin))->assertJsonPath('questions.0.choices', [0, 2]);
        $this->travel(61)->seconds();
        $this->postJson(route('eduquiz.answer', $room->pin), ['position' => 0, 'choices' => []])->assertConflict();
        app(Play::class)->expireDue();
        $this->getJson(route('eduquiz.state', $room->pin))->assertJsonPath('result.score', 1)->assertJsonPath('result.elapsed_seconds', 60)->assertJsonPath('retry', true);

        $response = $this->postJson(route('eduquiz.retry', $room->pin))->assertNoContent();
        $this->withCookie('eduquiz_'.$room->pin, $first);
        $this->getJson(route('eduquiz.state', $room->pin))->assertForbidden();
        $this->withCookie('eduquiz_'.$room->pin, $response->getCookie('eduquiz_'.$room->pin)->getValue());
        $this->postJson(route('eduquiz.submit', $room->pin))->assertNoContent();
        $this->postJson(route('eduquiz.submit', $room->pin))->assertNoContent();
        $this->postJson(route('eduquiz.retry', $room->pin))->assertConflict();
        $this->assertDatabaseCount('eduquiz_results', 2);
    }

    public function test_verified_room_requires_an_unused_code_bound_to_the_invited_contact(): void
    {
        $admin = User::factory()->create();
        $room = $this->room($admin, 'self', ['requires_invite' => true]);
        $this->actingAs($admin)->post(route('eduquiz.staff.rooms.invite', $room), ['contact' => 'HocVien@example.test'])->assertRedirect();
        $code = session('invite_code');
        $this->assertNotEmpty($code);
        Auth::forgetGuards();
        $this->post(route('eduquiz.join.store'), $this->joinData($room, ['invite' => $code]))->assertSessionHasErrors('invite');
        $this->join($room, ['email' => ' HOCVIEN@example.test ', 'invite' => $code]);
        $this->assertDatabaseHas('eduquiz_participants', ['email' => 'hocvien@example.test']);
        $this->assertNotNull(DB::table('eduquiz_invitations')->sole()->used_at);
        $this->assertNotSame($code, DB::table('eduquiz_invitations')->sole()->token_hash);
    }

    public function test_instructors_are_isolated_from_cms_other_instructors_and_settings(): void
    {
        $instructor = User::factory()->create();
        DB::table('eduquiz_instructors')->insert(['user_id' => $instructor->id]);
        $other = User::factory()->create();
        $private = $this->room($other, 'live', ['title' => 'Bộ đề của người khác']);
        $owned = $this->room($instructor);
        $this->actingAs($instructor);
        $this->get('/admin')->assertForbidden();
        $this->get('/giang-vien')->assertRedirect(route('eduquiz.staff.index'));
        $this->get(route('eduquiz.staff.settings'))->assertForbidden();
        $this->get(route('eduquiz.staff.rooms'))->assertOk()->assertDontSee($private->title);
        $this->getJson(route('eduquiz.staff.rooms.state', $private))->assertForbidden();
        $this->postJson(route('eduquiz.staff.rooms.control', $private), ['action' => 'start', 'version' => 0])->assertForbidden();
        $this->get(route('eduquiz.staff.rooms.show', $owned))->assertOk();
        $this->actingAs($other)->get('/admin')->assertOk();
    }

    public function test_import_reports_and_privacy_apply_to_real_data(): void
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);
        $csv = "question,a,b,c,d,e,f,correct,seconds,explanation\nCâu hỏi,Một,Hai,,,,,B,30,Giải thích\n";
        $this->post(route('eduquiz.staff.questions.import'), [
            'file' => UploadedFile::fake()->createWithContent('cau-hoi.csv', $csv),
        ])->assertRedirect()->assertSessionHasNoErrors();
        $this->assertDatabaseHas('eduquiz_questions', ['prompt' => 'Câu hỏi']);
        $template = $this->get(route('eduquiz.staff.questions.template'))->assertOk()->streamedContent();
        $this->post(route('eduquiz.staff.questions.import'), [
            'file' => UploadedFile::fake()->createWithContent('cau-hoi.xlsx', $template),
        ])->assertRedirect()->assertSessionHasNoErrors();
        $this->assertDatabaseHas('eduquiz_questions', ['prompt' => 'Nhập câu hỏi']);

        $room = $this->room($admin, 'self');
        $this->join($room, ['name' => 'Nguyễn Thị Hương', 'email' => 'huong@example.test']);
        $this->postJson(route('eduquiz.submit', $room->pin))->assertNoContent();
        $this->get(route('eduquiz.staff.results', ['q' => 'huong@example.test', 'session' => $room->id]))
            ->assertOk()->assertSee('Nguyễn Thị Hương');
        $this->get(route('eduquiz.staff.results.detail', Attempt::sole()))->assertOk();
        $xlsx = $this->get(route('eduquiz.staff.results.export', ['format' => 'xlsx', 'session' => $room->id]))->assertOk();
        $this->assertStringStartsWith("PK\x03\x04", $xlsx->streamedContent());
        $pdf = $this->get(route('eduquiz.staff.results.export', ['format' => 'pdf', 'session' => $room->id]))->assertOk();
        $this->assertStringStartsWith('%PDF', $pdf->getContent());

        $this->control($admin, $room, 'finish', 0)->assertNoContent();
        $this->actingAs($admin)->post(route('eduquiz.staff.settings.save'), ['retention_days' => 1])->assertRedirect();
        $this->assertDatabaseHas('eduquiz_settings', ['id' => 1, 'retention_days' => 1]);
        $this->travel(2)->days();
        app(Privacy::class)->anonymizeDue();
        $this->assertDatabaseHas('eduquiz_participants', ['session_id' => $room->id, 'name' => 'Học viên đã ẩn danh', 'email' => null]);
        $this->assertDatabaseHas('eduquiz_session_participants', ['token_hash' => null]);
        $this->assertDatabaseCount('eduquiz_results', 1);
    }

    private function question(string $prompt): Question
    {
        $this->post(route('eduquiz.staff.questions.save'), $this->questionData($prompt))->assertRedirect()->assertSessionHasNoErrors();

        return Question::where('prompt', $prompt)->sole();
    }

    private function questionData(string $prompt): array
    {
        return ['prompt' => $prompt, 'seconds' => 30, 'options' => [['text' => 'Scope 1'], ['text' => 'Scope 2', 'correct' => '1']]];
    }

    private function room(User $owner, string $mode = 'live', array $attributes = []): Room
    {
        $quiz = Quiz::create(['owner_id' => $owner->id, 'title' => 'Ôn tập GHG Protocol']);

        return Room::create([
            'owner_id' => $owner->id, 'quiz_id' => $quiz->id,
            'title' => $quiz->title, 'pin' => (string) (12345678 + Room::count()),
            'mode' => $mode, 'attempt_limit' => 1, 'duration_seconds' => 60,
            'requires_invite' => false, 'questions' => [[
                'prompt' => 'Điện mua được hạch toán vào phạm vi nào?',
                'seconds' => 30, 'explanation' => 'Điện mua thuộc Scope 2.', 'image' => null,
                'options' => [['text' => 'Scope 1', 'correct' => false], ['text' => 'Scope 2', 'correct' => true]],
            ]],
            ...$attributes,
        ]);
    }

    private function joinData(Room $room, array $extra): array
    {
        return [...['name' => 'Học viên', 'email' => 'vien@example.test', 'pin' => $room->pin, 'consent' => '1'], ...$extra];
    }

    private function join(Room $room, array $extra = []): string
    {
        $name = 'eduquiz_'.$room->pin;
        $response = $this->post(route('eduquiz.room.join', $room->pin), $this->joinData($room, $extra))
            ->assertRedirect(route('eduquiz.room', $room->pin))->assertSessionHasNoErrors()->assertCookie($name);
        $cookie = $response->getCookie($name);
        $this->assertTrue($cookie->isHttpOnly());
        $this->assertSame('lax', $cookie->getSameSite());
        $this->withCredentials()->withCookie($name, $cookie->getValue());

        return $cookie->getValue();
    }

    private function control(User $owner, Room $room, string $action, int $version): TestResponse
    {
        $response = $this->actingAs($owner)->postJson(route('eduquiz.staff.rooms.control', $room), compact('action', 'version'));
        Auth::forgetGuards();

        return $response;
    }
}
