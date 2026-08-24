@if ($paginator->hasPages())
    <nav class="d-flex justify-content-center">
        <ul class="pagination pagination-sm mb-0">
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">Anterior</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}">Anterior</a>
                </li>
            @endif

            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}">Próxima</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">Próxima</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
