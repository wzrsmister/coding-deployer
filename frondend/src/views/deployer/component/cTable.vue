<template>
<div>
    <div class="pull-right">
        <el-popover
          placement="bottom"
          ref="settingPopover"
          width="50"
          trigger="click">
          <div class="dndList-list" :style="{width:50}">
                <draggable :list="columns" class="dragArea" :options="{group:'setting'}" @start="dragging=true" @end="dragging=false">
                  <el-checkbox v-show="column.prop" :label="column.label" name="setting" v-for="(column, key) in columnsList" :checked="!column.noDisplay" :key="'setting-'+key" :style="{margin: '8px 0', display: 'block'}" @change="isDisplay(key, $event)"></el-checkbox>
                </draggable>
                <el-button slot="footer" >重置</el-button>
          </div>
        </el-popover>
        <el-button v-popover:settingPopover>设置</el-button>
    </div>
<el-table 
  :data="tableData" 
  v-loading.body="listLoading" 
  element-loading-text="拼命加载中" 
  v-on="$listeners"
>
<slot name="tableBody">
  <c-td 
    v-if="!column.noDisplay" 
    v-for="(column, key) in columnsList" 
    v-bind="column" 
    :key="key" 
    :index="key"
    v-on="$listeners"
    >
      <!-- <template :slot="column.prop" v-if="typeof $scopedSlots[column.prop] !== 'undefined'">
        <slot
          :name="column.prop"
          v-bind="$attrs"
          >
        </slot>
      </template> -->

      <template v-if="typeof $scopedSlots[column.unique] !== 'undefined'" slot-scope="scope">
          <slot :name="column.unique" v-bind="scope"></slot>
      </template>

      <!-- <template slot="name" >
        <slot name="name"></slot>
      </template> -->
  </c-td>
  <!-- <el-table-column 
    v-if="item.type == 'selection'"
    v-for="(item,key) in columnsList" v-bind="item" :key="key"
   >
  </el-table-column> -->
  <!-- <el-table-column 
    v-if="!item.noDisplay"
    v-for="(item,key) in columnsList" v-bind="item" :key="key"
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
  </el-table-column> -->
</slot>
 </el-table>
</div>
</template>

<script>
import Vue from 'vue'
import cTd from './cTd'
import draggable from 'vuedraggable'

export default{
 name: 'cTable',
 components: { draggable, cTd },
 props:{
    tableData: {}, 
    listLoading: {}, 
    events: {},
    columns: {
        default: []
    },
    mColumns: []
 },
 data(){
    return {
        dragging: false
    }
 },
 mounted(){
    //this.$slots.tableBody = this.$slots.default
    //console.info(this.$slots)
    const setting = true;
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
 computed: {
    columnsList: {
        get(){
            return this.columns.filter(v => {
                v.noDisplay = v.noDisplay ? v.noDisplay : false;
                return v
            })
        },
        set(value){

        }
    },
    settings: {
      get(){
        return this.columns.filter(v => {
            return v
        })
      },
      set(value) {
         this.$store.commit(this.$props.columns, value)
      }
    }
  },
 methods: {
    isDisplay (key, $event){
        let column = this.columns[key]
        column.noDisplay = !$event;
        Vue.set(this.columns, key, column)
    }
 }
}
</script>