@if ($page->hasPages())
    <nav class="pagination actions" aria-label="Phân trang">
        @if ($page->previousPageUrl())<a class="button quiet" rel="prev" href="{{ $page->previousPageUrl() }}">Trang trước</a>@endif
        <span class="muted">Trang {{ $page->currentPage() }} / {{ $page->lastPage() }}</span>
        @if ($page->nextPageUrl())<a class="button quiet" rel="next" href="{{ $page->nextPageUrl() }}">Trang sau</a>@endif
    </nav>
@endif

