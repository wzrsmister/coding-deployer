<template>
  <div class="app-container">
    <c-data-grid 
      :dataLoadHandler="dataLoadHandler" 
      :searchQueryHandler="searchQueryHandler" 
      :formatRowData="formatRowData" 
      :paginations="paginations"
      :events="events"
      :height="20"
      @ccc="cccHandler"
    >
      <template slot="tableBody">
        <c-td key="id"  prop="id" label="ID" ></c-td>
        <c-td key="name" prop="name" label="Name" >
            <el-button size="mini" slot-scope="scope">{{ scope.row.name }}</el-button>
        </c-td>
      </template>

      <!-- <template slot="name" slot-scope="{ row }">
          <el-button size="mini">{{ row.name }}</el-button>
      </template> -->

    </c-data-grid>
  </div>
</template>


<script>
import { createElement } from 'vue'
import cDataGrid from '../component/cDataGrid'
import cTd from '../component/cTd'
import { getList } from '@/api/user'
import { parseTime } from '@/utils/index'
export default {
  components: { cDataGrid, cTd },
  data() {
    return {
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
        {type: 'selection', label: "全选"},
        {type: 'expand'},
        {type: 'index'},
        {prop: 'id', label: 'ID', sortable: 'custom', fixed: true},
        {prop: 'create_id', label: '创建人ID', value: '<div>123456</div>'},
        {prop: 'name', label: '名字', sortable: true, render: (value, index, row) => {
          //console.info(value)
          return `<el-button type="success" @click="$emit('ccc')">${value}</el-button>`
        }},
        {prop: 'email', label: '邮箱', noDisplay: true, value: (value, index, row) => {
          return parseTime(new Date().getTime())
        }},
        {prop: 'status', label: '状态', template: `status: {{$attrs.value}}, index: {{$attrs.index}}`},
        {prop: 'created_at', label: '创建时间', template: '123{{$attrs.row.created_at}}'},
        {prop: 'login_at', label: '最后登陆'},
        {prop: 'updated_at', label: '最后更新'},
        {prop: 'deleted_at', label: '删除时间'},
        {prop: 'remark', label: '备注'},
        {prop: 'hander', label: '操作', fixed: "right", template: `<a href="/data/web">查看</a>`}
      ]
    }
  },
  methods: {
    cccHandler(){
      console.info(111)
    },
    getSearchQuery(query){
      return Object.assign(query, {
          sex: 'sex',
          email: 'email',
      })
    },
    showRowData(data){
      return data
    }
  }
}
</script>
