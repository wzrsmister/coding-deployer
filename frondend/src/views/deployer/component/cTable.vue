<template>
<el-table 
  :data="tableData" 
  v-loading.body="listLoading" 
  element-loading-text="拼命加载中" 
  v-bind="attributes"
  v-on="events"
>
  <el-table-column v-for="(item,key) in columns" v-bind="item">
    <template scope="scope">
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
        <div v-else v-text="scope.row[item.prop]"></div>
    </template>
  </el-table-column>
  </el-table-column>
 </el-table>
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
    this.$props.columns.forEach((column)=>{
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

 }
}
</script>