<template>
  <div class="app-container">
    <c-table 
      :tableData="tableData"
      :listLoading="listLoading"
      v-bind="$props"
      v-on="$listeners"
      :columns="mColumns"
      @sort-change="mSortChangeHandler"
      >
        <!-- <template :slot="typeof $scopedSlots[column.prop] !== 'undefined' ? column.prop : ''" slot-scope="props"  v-for="(column,key) in columns" v-bind="column">
            <slot :name="column.prop" v-bind="props"></slot>
        </template> -->

        <!--<template slot="tableBody" v-if="typeof $scopedSlots['tableBody'] === 'undefined'"> -->
            <slot name="tableBody"></slot>
        <!--</template>-->

        <template 
          :slot="typeof $scopedSlots[column.prop] !== 'undefined' ? column.prop : ''" 
          v-for="(column,key) in mColumns"
          slot-scope="scope"
        >
            <slot :name="column.prop" v-bind="scope"></slot>
        </template>
        
        <!-- <template slot="name" >
            <slot name="name"></slot>
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
            default: () => []
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
            attrColumns: []
        }
    },
    //created() {
    mounted() {
        console.info(this)
        if(this.$slots.hasOwnProperty("tableBody")){
            this.$slots.tableBody.map((slot, index) => {
                if(slot.data != undefined && slot.data.hasOwnProperty("attrs")){
                    let column = slot.data.attrs
                    column.node = slot
                    this.attrColumns.push(column)
                }
            })
        }
        console.info(this.$children[0].$slots)
        this.$children[0].$slots.tableBody = this.attrColumns
        this.$children[0].$slots.default = []
        console.info(this.$children[0].$slots)
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
        mColumns: function(){
            return this.columns.concat(this.attrColumns)
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
