<template>
    <el-table-column v-if="noTemplate" v-bind="$attrs"></el-table-column>
    <el-table-column v-else v-bind="$attrs">
        <template slot-scope="scope">
            <template v-if="$attrs.value">
              {{ 
                typeof $attrs.value == 'function' ? 
                $attrs.value(scope.row[$attrs.prop], scope.$index, scope.row) : 
                $attrs.value 
              }}
            </template>
            <div v-else-if="$attrs.render">
              {{renderTemplate($attrs.render(scope.row[$attrs.prop], scope.$index, scope.row))}}
            </div>
        </template>
    </el-table-column>
</template>

<script>
import Vue from 'vue'

export default{
  name: 'cTd',
  props:{
    index: {}
  },
  data(){
    return {
    }
  },
  mounted(){
    
  },
  computed: {
    noTemplate: function () {
      return !this.$attrs.hasOwnProperty("value") 
      &&     !this.$attrs.hasOwnProperty("render") 
      &&     !this.$attrs.hasOwnProperty("component") 
      &&     !this.$attrs.hasOwnProperty("template") 
      &&     !this.$attrs.hasOwnProperty("html") 
    }
  },
  render: function (createElement) {

  },
  created() {
  },
  methods: {
    renderTemplate(template){
      const compile = Vue.compile(template);
      console.info(this.$options.render)
      console.info(this.$options.template)
      return compile.render
    },
    compile () {
        if (this.column.render) {
            const $parent = this.content;
            const template = this.column.render(this.row, this.column, this.index);
            const cell = document.createElement('div');
            cell.innerHTML = template;
            const _oldParentChildLen = $parent.$children.length;
            $parent.$compile(cell);    // todo 这里无法触发 ready 钩子
            const _newParentChildLen = $parent.$children.length;
            if (_oldParentChildLen !== _newParentChildLen) {    // if render normal html node, do not tag
                this.uid = $parent.$children[$parent.$children.length - 1]._uid;    // tag it, and delete when data or columns update
            }
            this.$el.innerHTML = '';
            this.$el.appendChild(cell);
        }
    },
  }
}
</script>