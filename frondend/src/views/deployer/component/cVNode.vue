<script>

export default{
  name: 'cVNode',
  props:[ 'node', 'unique' ],
  data(){
    return {
    }
  },
  
  render: function (createElement) {
    console.info(this.unique)
    return this.node
  }
}
</script>