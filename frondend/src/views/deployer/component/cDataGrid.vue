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
import Sortable from 'sortablejs'
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
                border: true,
                setting: {
                    prop: this.$props.columns[this.$props.columns.length - 1].prop,
                }
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
                this.$nextTick(() => {
                    this.setSort()
                })
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
        sortChange({column, prop, order}) {
            if(prop){
                this.$props.sort = prop + '.' + (order == 'descending' ? 'desc' : 'asc') 
            }else{
                this.$props.sort = '' 
            }
            this.fetchData();
        },
        setSort(){
            console.info(555)
            const el = document.querySelectorAll('.el-table__body-wrapper > table > tbody')[0]
            console.info(el)
            this.sortable = Sortable.create(el, {
                ghostClass: 'sortable-ghost', // Class name for the drop placeholder,
                setData: function(dataTransfer) {
                    console.info('dataTransfer', dataTransfer)
                  //dataTransfer.setData('Text', '')
                  // to avoid Firefox bug
                  // Detail see : https://github.com/RubaXa/Sortable/issues/1012
                },
                onEnd: evt => {
                    console.info('evt', evt)
                  /*const targetRow = this.list.splice(evt.oldIndex, 1)[0]
                  this.list.splice(evt.newIndex, 0, targetRow)

                  // for show the changes, you can delete in you code
                  const tempIndex = this.newList.splice(evt.oldIndex, 1)[0]
                  this.newList.splice(evt.newIndex, 0, tempIndex)*/
                }
            })
        }
    }
}
</script>
