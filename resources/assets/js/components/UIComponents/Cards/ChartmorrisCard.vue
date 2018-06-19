<template>
  <div class="card">
    <div class="card-header" v-if="$slots.header">
      <slot name="header"></slot>
    </div>
    <div class="card-body">
      <donut-chart
        v-if="donut" 
        :id="id"
        :data="donutData.data" 
        :colors="donutData.colors"  
        :resize="donutData.resize">
      </donut-chart>
      <bar-chart
         v-if="bar" 
         :id="id"
         :data="barData.data" 
         :xkey="barData.xkey"
         :ykeys="barData.ykeys"
         :labels="barData.labels"
         :bar-colors="barData.barcolors" 
         :grid="barData.grid"
         :grid-text-weight="barData.gridtextweight"
         :hideHover= "barData.hidehover"
         :resize="barData.resize"
         :pointSize="barData.pointsize"
         :lineWidth="barData.linewidth"
         :fillOpacity="barData.fillopacity"
         :behaveLikeLine="barData.behavelikeLine"
         :gridLineColor="barData.gridlineColor">
       </bar-chart>
       <line-chart
         v-if="line" 
         :id="id"
         :data="lineData.data" 
         :xkey="lineData.xkey"
         :ykeys="lineData.ykeys"
         :labels="lineData.labels"
         :line-colors="lineData.linecolors" 
         :grid="lineData.grid"
         :grid-text-weight="lineData.gridtextweight"
         :hideHover= "lineData.hidehover"
         :resize="lineData.resize"
         :pointSize="lineData.pointsize"
         :lineWidth="lineData.linewidth"
         :fillOpacity="lineData.fillopacity"
         :behaveLikeLine="lineData.behavelikeLine"
         :gridLineColor="lineData.gridlineColor">
      </line-chart>
      <area-chart
         v-if="area" 
         :id="id"
         :data="areaData.data" 
         :xkey="areaData.xkey"
         :ykeys="areaData.ykeys"
         :labels="areaData.labels"
         :line-colors="areaData.linecolors" 
         :grid="areaData.grid"
         :grid-text-weight="areaData.gridtextweight"
         :hideHover= "areaData.hidehover"
         :resize="areaData.resize"
         :pointSize="areaData.pointsize"
         :lineWidth="areaData.linewidth"
         :behaveLikeLine="areaData.behavelikeLine"
         :gridLineColor="areaData.gridlineColor">
      </area-chart>
    </div>
    <div class="card-footer" v-if="$slots.footer">
      <slot name="footer"></slot>
    </div>
  </div>
</template>
<script>
 
  
 import {DonutChart, BarChart, LineChart, AreaChart } from 'vue-morris'

  export default {
    name: 'chart-card',
    components: {
      'Card': () => import('./Card.vue'),
     DonutChart, 
     BarChart, 
     LineChart, 
     AreaChart
    },
    props: {
      id: '',
      donut: false,
      bar: false,
      line: false,
      area: false,
      donutData: {
        type: Object,
        default: () => {
          return {
          }
        }
      },
      barData: {
        type: Object,
        default: () => {
          return {
          }
        }
      },
      lineData: {
        type: Object,
        default: () => {
          return {
          }
        }
      },
      areaData: {
        type: Object,
        default: () => {
          return {
          }
        }
      }
      
    },
    data () {
      return {
      }
    },
   
  };
</script>
<style>

</style>
