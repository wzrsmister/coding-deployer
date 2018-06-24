<template>
  <div class="app-container">
    <c-table 
      :tableData="tableData"
      :listLoading="listLoading"
      v-bind="$props"
      v-on="$listeners"
      @sort-change="mSortChangeHandler"
      >
        <!-- <template slot="table-body">
            <slot name="table-body"></slot>
        </template> -->
    </c-table>
    <c-pagination 
      v-bind="mPaginations" 
      @current-change="handleCurrentChange"
      @size-change="handleSizeChange">
    </c-pagination>
  </div>

</template>

<script>
import Sortable from 'sortablejs'
import cTable from './cTable'
import cPagination from './cPagination'
export default {
    name: 'cDataGrid',
    components: { cTable, cPagination},
    props: {
        events: {
            default: {}
        },
        paginations: {
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
        },
        sortChangeHandler: undefined
    },
    data() {
        return {
            sort: '',
            listLoading: false,
            tableData: [],
        }
    },
    mounted() {
        this.fetchData()
    },
    computed: {
        mPaginations: function (){
            return Object.assign({
                page: 1,
                total: 0,
                currentPage: 1,
                pageSize: 2,        
            }, this.$props.paginations)
        },
        mSortChangeHandler: function(){
            if(this.$props.sortChangeHandler !== undefined){
                return this.$props.sortChangeHandler
            }else{
                return ({column, prop, order}) => {
                    if(prop){
                        this.sort = prop + '.' + (order == 'descending' ? 'desc' : 'asc') 
                    }else{
                        this.sort = '' 
                    }
                    this.fetchData();
                }
            }
        }
    },
    methods: {
        getDefaultSearchQuery(){
            return {
                sort: this.sort,
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
            }).catch(() => {
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
    }
}
</script>
