<script>

export default{
  name: 'cVNode',
  props:[ 'node' ],
  data(){
    return {
    }
  },
  
  render: function (createElement) {
    return this.node
  }
}
</script>