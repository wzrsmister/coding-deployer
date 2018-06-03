<template>
<div>
<el-popover
  placement="right"
  ref="settingPopover"
  width="400"
  trigger="click">
  <el-table :data="tableData">
    <el-table-column width="150" property="id" label="日期"></el-table-column>
    <el-table-column width="100" property="name" label="姓名"></el-table-column>
  </el-table>
</el-popover>
<el-button v-popover:settingPopover>focus 激活</el-button>
<el-table 
  :data="tableData" 
  v-loading.body="listLoading" 
  element-loading-text="拼命加载中" 
  v-bind="attributes"
  v-on="events"
>
<slot name="table-body">
  <el-table-column 
    v-if="item.type == 'selection'"
    v-for="(item,key) in columns" v-bind="item" :key="key"
   >
  </el-table-column>

  <el-table-column 
    v-if="item.type != 'selection'"
    v-for="(item,key) in columns" v-bind="item" :key="key"
   >
    <template slot-scope="scope">
        <div v-if="item.value" 
            v-text="typeof item.value == 'function' ? 
            item.value(scope.row[item.prop], scope.$index, scope.row) : item.value"
        >
        </div>
        <div v-else-if="item.render" 
            v-html="item.render(scope.row[item.prop], scope.$index, scope.row)"
        >
        </div>
        <component v-else-if="item.component" 
            :is="'__component_' + item.prop + '__'" 
            :$index="scope.$index"
            :$value="scope.row[item.prop]"
            :$scope="scope"
            :$row="scope.row"
            :$column="scope.column"
        ></component>
        <component v-else-if="item.template" 
            :is="'__template_' + item.prop + '__'" 
            :$index="scope.$index"
            :$value="scope.row[item.prop]"
            :$scope="scope"
            :$row="scope.row"
            :$column="scope.column"
        ></component>
        <div v-else-if="item.html" v-html="scope.row[item.prop]"></div>
        <div v-else-if="item.prop" v-text="scope.row[item.prop]"></div>
    </template>
  </el-table-column>
</slot>
 </el-table>
</div>
</template>

<script>
export default{
 name: 'cTable',
 components: {},
 props:{
    tableData: {}, 
    listLoading: {}, 
    attributes: {},
    events: {},
    columns: {}
 },
 created(){
    const setting = this.$props.attributes.hasOwnProperty("setting") ? this.$props.attributes.setting : false;
    this.$props.columns.forEach((column)=>{
        if(setting && setting.prop == column.prop){
            let setting = []
            this.$props.columns.forEach(item => {
                if(item.prop) setting.push({text: item.label || item.prop, value: item.prop})
            })
            column.renderHeader = this.renderSettingHeader
            column.setting = setting
            this.$props.columns[4].filters = setting
        }
        if(column.hasOwnProperty('component')){
            this.$options.components['__component_' + column.prop + '__'] = column.component 
        }else if(column.hasOwnProperty('template')){
            if(!column.template.trim().startsWith('<')){
                column.template = '<div>' + column.template + '</div>'
            }
            const component = {
              props: ['$row', '$value', '$index', '$scope', '$column'],
              template: column.template
            }
            this.$options.components['__template_' + column.prop + '__'] = component 
        }

    })
 },
 data(){
    return{
    
    }
 },
 methods: {
    renderSettingHeader(createElement, { column, _self }){
        console.info(this.$refs)
        return [
            column.label,
            createElement('span', 
                {
                    'class': 'el-table__column-setting-trigger',
                }, 
                [
                    createElement('i', {
                        'class': 'el-icon-setting',
                        'props': {
                            'popover': this.$refs.settingPopover,
                            'v-popover': this.$refs.settingPopover
                        },
                        directives: [
                            {
                                'v-popover': this.$refs.settingPopover
                            }
                        ]
                    }),
                    /*createElement('el-popover', {
                        props: {
                            placement: 'top-start', 
                            title: '标题', 
                            width: '200', 
                            trigger: 'click', 
                            content: '这是一段内容,这是一段内容,这是一段内容,这是一段内容。'}}, [
                            h('i', {slot: 'reference', class: 'el-icon-question', style: 'color:gray;font-size:16px;margin-left:10px;'}, '')
                        ])*/
                ]
            )
        ]
    }
 }
}
</script>