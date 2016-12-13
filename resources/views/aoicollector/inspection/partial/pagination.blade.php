<div ng-init="perpage = {{ $inspectionList->porPagina }}; currentPage = {{ $inspectionList->pagina }}; bigTotalItems = '{{ $inspectionList->filas }}'; maxSize = 10;">
    <pagination items-per-page="perpage" total-items="bigTotalItems" ng-model="currentPage" max-size="maxSize" class="pagination-sm" boundary-links="true" rotate="false" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo; Primera" last-text="Ultima &raquo;" ng-change="pageChanged('{{ url('aoicollector/inspection/show/'.$maquina->id) }}')"></pagination>
</div>

