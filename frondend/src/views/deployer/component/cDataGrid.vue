<template>
  <div class="app-container">
    <c-table 
      :attributes="mAttributes"
      :tableData="tableData" 
      :columns="columns" 
      :listLoading="listLoading"
      :events="mEvents"
      >
        <template slot="table-body">
            <slot name="table-body"></slot>
        </template>
    </c-table>
    <c-pagination 
      v-bind="mPaginations" 
      @current-change="handleCurrentChange"
      @size-change="handleSizeChange">
    </c-pagination>
  </div>

</template>

<script>
import cTable from './cTable'
import cPagination from './cPagination'
export default {
    name: 'cDataGrid',
    components: { cTable, cPagination},
    props: {
        sort: {},
        events: {
            default: {}
        },
        paginations: {
            default: {}
        },
        attributes: {
            default: {}
        },
        columns: {
            default: {}
        },
        dataLoadHandler: {

        },
        searchQueryHandler: {
            default: () => (query) => query 
        },
        formatRowData: {
            default: () => (data) => data 
        }
    },
    data() {
        return {
            mEvents: Object.assign({
                'sort-change': this.sortChange,
            }, this.$props.events),
            mAttributes: Object.assign({
                border: true
            }, this.$props.attributes),
            mPaginations: Object.assign({
                page: 1,
                total: 0,
                currentPage: 1,
                pageSize: 2,        
            }, this.$props.paginations),
            tableData: [],
            listLoading: false,
        }
    },
    created() {
        this.fetchData()
    },
    methods: {
        getDefaultSearchQuery(){
            return {
                sort: this.$props.sort,
                page: this.mPaginations.currentPage,
                pagesize: this.mPaginations.pageSize,
            }
        },
        fetchData() {
            this.listLoading = true
            this.$emit("fetch-data-start")
            this.$props.dataLoadHandler(this.$props.searchQueryHandler(this.getDefaultSearchQuery()))
            .then(({ data }) => {
                this.tableData = data.data.list.map(item => this.$props.formatRowData(item))
                this.mPaginations.total = data.data.total;
                this.listLoading = false
                this.$emit("fetch-data-end", true)
            }).then(() => {
                this.listLoading = false
                this.$emit("fetch-data-end", false)
            })
        },
        handleCurrentChange(currentPage) {
            this.$emit("current-change", currentPage)
            this.mPaginations.currentPage = currentPage
            this.fetchData();
        },
        handleSizeChange(pageSize) {
            this.$emit("size-page", pageSize)
            this.mPaginations.pageSize = pageSize
            this.fetchData();
        },
        sortChange({column, prop, order}) {
            if(prop){
                this.$props.sort = prop + '.' + (order == 'descending' ? 'desc' : 'asc') 
            }else{
                this.$props.sort = '' 
            }
            this.fetchData();
        }
    }
}
</script>
