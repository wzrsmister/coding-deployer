<template>
  <div class="app-container">
    <c-data-grid 
      :dataLoadHandler="dataLoadHandler" 
      :searchQueryHandler="searchQueryHandler" 
      :formatRowData="formatRowData" 
      :columns="columns"
      :attributes="attributes"
      :paginations="paginations"
      :events="events"
    >
        <!-- <template slot="table-body">
          <el-table-column 
              prop='id'
              label='主键'
          >
            <template slot-scope="{row, index}">{{props.row.id}}</template>
          </el-table-column>
          <el-table-column >
            <template slot-scope="{}">IDDDDDDDDDD</template>
          </el-table-column>
        </template> -->
    </c-data-grid>
  </div>
</template>


<script>
import { createElement } from 'vue'
import cDataGrid from '../component2/cDataGrid'
import { getList } from '@/api/test'
import { parseTime } from '@/utils/index'
export default {
  components: { cDataGrid },
  data() {
    return {
      attributes: {
          style: "width: 100%",
          height: "250",
          //setting: {prop: "hander"},
      },
      paginations: {},
      dataLoadHandler: getList,
      searchQueryHandler: this.getSearchQuery,
      formatRowData: this.showRowData,
      events: {
          'cell-click': (count) => console.info('cell-click', count),
          'cell-click.once': () => console.info('cell-click.once'),
          'cell-dblclick': () => console.info('cell-dblclick'),
          //'sort-change': () => console.info('sort-change'),
      },
      statusArr: {
        '1' : { name: "正常", label: 'info' },
        '0' : { name: "未启用", label: '' },
        '-1': { name: "禁用", label: '' }
      },
      columns: [
        {type: 'selection'},
        {type: 'expand'},
        {type: 'index'},
        {prop: 'id', label: 'ID', sortable: 'custom', fixed: true},
        {prop: 'create_id', label: '创建人ID', value: '<div>123456</div>'},
        {prop: 'name', label: '名字', sortable: true},
        {prop: 'email', label: '邮箱', noDisplay: true, value: (value, index, row) => {
          //console.info(this, value, index, row)
          return parseTime(value)
        }},
        /*{prop: 'email', label: '邮箱2', render: (value, index, row) => {
            return `<div>${index}.${value}</div>`
        }},*/
        {prop: 'status', label: '状态', template: `status: {{$value}}, index: {{$index}}`},
        {prop: 'created_at', label: '创建时间', template: '123{{$row.created_at}}'},
        {prop: 'login_at', label: '最后登陆'},
        {prop: 'updated_at', label: '最后更新'},
        {prop: 'deleted_at', label: '删除时间'},
        {prop: 'remark', label: '备注'},
        {prop: 'hander', label: '操作', fixed: "right", template: `<a href="/data/web">查看</a>`}
      ]
    }
  },
  methods: {
    getSearchQuery(query){
      return Object.assign(query, {
          sex: 'sex',
          email: 'email',
      })
    },
    getStatus(key){
      if(this.statusArr.hasOwnProperty(key)){
        return this.statusArr[key]
      }else{
        return {}
      }
    },
    showRowData(data){
      // var status = this.getStatus(data.status)
      // data.status = status.name
      // data.created_at = parseTime(data.created_at)
      // data.login_at = parseTime(data.login_at)
      // data.deleted_at = parseTime(data.deleted_at)
      // data.hander = '<a>查看</a>'
      return data
    }
  }
}
</script>
