<template>
  <div class="content">
    <div class="container-fluid">
      <!-- Count section -->
      <div class="row">
        <div class="col-xl-3 col-md-6">
          <stats-card>
            <div slot="header" class="icon-warning">
              <i class="nc-icon nc-chart text-warning"></i>
            </div>
            <div slot="content">
              <p class="card-category">Capacity</p>
              <h4 class="card-title">105GB</h4>
            </div>
            <div slot="footer">
              <i class="fa fa-refresh"></i>Updated now
            </div>
          </stats-card>
        </div>

        <div class="col-xl-3 col-md-6">
          <stats-card>
            <div slot="header" class="icon-success">
              <i class="nc-icon nc-light-3 text-success"></i>
            </div>
            <div slot="content">
              <p class="card-category">Revenue</p>
              <h4 class="card-title">$1,345</h4>
            </div>
            <div slot="footer">
              <i class="fa fa-calendar-o"></i>Last day
            </div>
          </stats-card>
        </div>

        <div class="col-xl-3 col-md-6">
          <stats-card>
            <div slot="header" class="icon-danger">
              <i class="nc-icon nc-vector text-danger"></i>
            </div>
            <div slot="content">
              <p class="card-category">Errors</p>
              <h4 class="card-title">23</h4>
            </div>
            <div slot="footer">
              <i class="fa fa-clock-o"></i>Last day
            </div>
          </stats-card>
        </div>

        <div class="col-xl-3 col-md-6">
          <stats-card>
            <div slot="header" class="icon-info">
              <i class="nc-icon nc-favourite-28 text-primary"></i>
            </div>
            <div slot="content">
              <p class="card-category">Followers</p>
              <h4 class="card-title">+45</h4>
            </div>
            <div slot="footer">
              <i class="fa fa-refresh"></i>Updated now
            </div>
          </stats-card>
        </div>
      </div>

      <!-- Vue morris Chart -->

      <div class="row">
        <div class="col-md-6">
        <chartmorris-card 
         id="line"
          :line="true"
          :line-data="lineData">

           <template slot="header">
             <h4 class="card-title">Yearly visitors</h4>
             <p v-show="ga" class="card-category text-success">Data from Google Analytics</p>
             <p v-show="dem" class="card-category text-warning">Data from Demo</p>
               <div class="text-center" v-show="loadArea">
                <fulfilling-bouncing-circle-spinner 
                       :animation-duration="4000"
                       :size="60"
                       :color="'#FFC107'"/>
                  <h3 class="text-warning text-center">Loading</h3>
                </div>
                   
           </template>
           <template slot="footer">
             <div class="legend">
               <i class="fa fa-circle text-info"></i> Visitors
               <i class="fa fa-circle text-danger"></i> Page Views
             </div>
             <hr>
             <div class="stats">
               <i class="fa fa-check"></i> Data information certified
             </div>
           </template>
         </chartmorris-card>
        </div>
        <div class="col-md-6">
         <chartmorris-card
         id="area"
          :area="true"
          :area-data="areaData">
           <template slot="header">
             <h4 class="card-title">This month visitors</h4>
             <p v-show="ga" class="card-category text-success">Data from Google Analytics</p>
             <p v-show="dem" class="card-category text-warning">Data from Demo</p>
             <div class="text-center" v-show="loadArea">
              <half-circle-spinner 
                     :animation-duration="4000"
                     :size="60"
                     :color="'#FFC107'"/>
                <h3 class="text-warning text-center">Loading</h3>
              </div>
             
           </template>
           <template slot="footer">
             <div class="legend">
               <i class="fa fa-circle text-info"></i> Visitors
               <i class="fa fa-circle text-danger"></i> Page Views
             </div>
             <hr>
             <div class="stats">
               <i class="fa fa-check"></i> Data information certified
             </div>
           </template>
         </chartmorris-card>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
         <chartmorris-card
         id="donut1"
          :donut="true"
          :donut-data="donutData">
           <template slot="header">
             <h4 class="card-title">Color Preference</h4>
             <p class="card-category">From test group people</p>
           </template>
           <template slot="footer">
             <div class="legend">
               <i class="fa fa-circle text-info"></i> Blue
               <i class="fa fa-circle text-danger"></i> Red
               <i class="fa fa-circle text-warning"></i> Yellow
             </div>
             <hr>
             <div class="stats">
               <i class="fa fa-check"></i> Data information certified
             </div>
           </template>
         </chartmorris-card>
        </div>
        <div class="col-md-6">
         <chartmorris-card
         id="bar"
          :bar="true"
          :bar-data="barData">
           <template slot="header">
             <h4 class="card-title">Users by platform</h4>
             <p class="card-category">From test group people</p>
           </template>
           <template slot="footer">
             <div class="legend">
              <i class="fa fa-circle text-danger"></i> Android
               <i class="fa fa-circle text-info"></i> ios
               <i class="fa fa-circle text-warning"></i> Windows
               
             </div>
             <hr>
             <div class="stats">
               <i class="fa fa-check"></i> Data information certified
             </div>
           </template>
         </chartmorris-card>
        </div>
      </div>

      <!-- Chartist and tasks -->
      <div class="row">
        <div class="col-md-8">
          <chart-card 
                      :chart-data="lineChart.data"
                      :chart-options="lineChart.options"
                      :responsive-options="lineChart.responsiveOptions">
            <template slot="header">
              <h4 class="card-title">Users Behavior</h4>
              <p class="card-category">24 Hours performance</p>
            </template>
            <template slot="footer">
              <div class="legend">
                <i class="fa fa-circle text-info"></i> Open
                <i class="fa fa-circle text-danger"></i> Click
                <i class="fa fa-circle text-warning"></i> Click Second Time
              </div>
              <hr>
              <div class="stats">
                <i class="fa fa-history"></i> Updated 3 minutes ago
              </div>
            </template>
          </chart-card>
        </div>

        <div class="col-md-4">
          <chart-card :chart-data="pieChart.data" chart-type="Pie" :chart-options="pieChart.options">
            <template slot="header">
              <h4 class="card-title">Email Statistics</h4>
              <p class="card-category">Last Campaign Performance</p>
            </template>
            <template slot="footer">
              <div class="legend">
                <i class="fa fa-circle text-info"></i> Open
                <i class="fa fa-circle text-danger"></i> Bounce
                <i class="fa fa-circle text-warning"></i> Unsubscribe
              </div>
              <hr>
              <div class="stats">
                <i class="fa fa-clock-o"></i> Campaign sent 2 days ago
              </div>
            </template>
          </chart-card>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <chart-card
            :chart-data="barChart.data"
            :chart-options="barChart.options"
            :chart-responsive-options="barChart.responsiveOptions"
            chart-type="Bar">
            <template slot="header">
              <h4 class="card-title">2014 Sales</h4>
              <p class="card-category">All products including Taxes</p>
            </template>
            <template slot="footer">
              <div class="legend">
                <i class="fa fa-circle text-info"></i> Tesla Model S
                <i class="fa fa-circle text-danger"></i> BMW 5 Series
              </div>
              <hr>
              <div class="stats">
                <i class="fa fa-check"></i> Data information certified
              </div>
            </template>
          </chart-card>
        </div>

        <div class="col-md-6">
          <card>
            <template slot="header">
              <h5 class="title">Tasks</h5>
              <p class="category">Backend development</p>
            </template>
            <l-table :data="tableData.data"
                     :columns="tableData.columns">
              <template slot="columns"></template>

              <template slot-scope="{row}">
                <td>
                  <Checkbox v-model="row.checked"></Checkbox>
                </td>
                <td>{{row.title}}</td>
                <td class="td-actions text-right">
                  <button type="button" class="btn-simple btn btn-xs btn-info" v-tooltip.top-center="editTooltip">
                    <i class="fa fa-edit"></i>
                  </button>
                  <button type="button" class="btn-simple btn btn-xs btn-danger" v-tooltip.top-center="deleteTooltip">
                    <i class="fa fa-times"></i>
                  </button>
                </td>
              </template>
            </l-table>
            <div class="footer">
              <hr>
              <div class="stats">
                <i class="fa fa-history"></i> Updated 3 minutes ago
              </div>
            </div>
          </card>

        </div>
      </div>

      
    </div>
  </div>
</template>
<script>

import { FulfillingBouncingCircleSpinner, HalfCircleSpinner } from 'epic-spinners'

  export default {
    components: {
      'Checkbox': () => import('components/UIComponents/Inputs/Checkbox.vue'),
      'Card': () => import('components/UIComponents/Cards/Card.vue'),
      'LTable': () => import('components/UIComponents/Table.vue'),
      'ChartCard': () => import('components/UIComponents/Cards/ChartCard.vue'),
      'ChartmorrisCard': () => import('components/UIComponents/Cards/ChartmorrisCard.vue'),
      'StatsCard': () => import('components/UIComponents/Cards/StatsCard.vue'),
      FulfillingBouncingCircleSpinner,
      HalfCircleSpinner 
    },
    data () {
      return {
        ga: false,
        dem: false,
        rendered: false,
        loadLine: true,
        loadArea: true,
        editTooltip: 'Edit Task',
        deleteTooltip: 'Remove',
        pieChart: {
          data: {
            labels: ['40%', '20%', '40%'],
            series: [40, 20, 40]
          },
          options: {
                    }
        },
        barData: {
          data: [
              { 'year': '2013', 'and': 10, 'ios': 5, 'win': 2 },
              { 'year': '2014', 'and': 10, 'ios': 15, 'win': 3 },
              { 'year': '2015', 'and': 20, 'ios': 25, 'win': 2 },
              { 'year': '2016', 'and': 30, 'ios': 20, 'win': 1 },
            ],
          barcolors: [ "#DC3545", "#17A2B8", "#FFC107"],
          xkey: 'year',
          ykeys: [ "and", "ios", "win" ],
          labels: [ "Android", "ios", "Windows" ],
          grid: "true",
          gridtextweight: "bold",
          hidehover: 'auto',
          resize: true,
          pointsize: 0,
          linewidth: 2,
          fillopacity: 1,
          behavelikeLine: true,
          gridlineColor: '#e0e0e0'

        },
        lineData: {
          
          data: [],
          linecolors: [ "#17A2B8", "#DC3545" ],
          xkey: 'date',
          ykeys: ['visitors', 'pageViews'],
          labels: ['Visitors', 'Page Views'],
          grid: "true",
          gridtextweight: "bold",
          hidehover: 'auto',
          resize: true,
          pointsize: 0,
          linewidth: 2,
          fillopacity: 'auto',
          behavelikeLine: true,
          gridlineColor: '#e0e0e0'
        },
        areaData: {
          data: [],
          linecolors: [ "#17A2B8", "#DC3545"],
          xkey: 'date',
          ykeys: ['visitors', 'pageViews'],
          labels: ['Visitors', 'Page Views'],
          grid: "true",
          gridtextweight: "bold",
          hidehover: 'auto',
          resize: true,
          pointsize: 0,
          linewidth: 2,
          behavelikeLine: false,
          gridlineColor: '#e0e0e0'
        },
        donutData: {
          data: [
              { label: 'Red', value: 300 },
              { label: 'Blue', value: 50 },
              { label: 'Yellow', value: 100 }
            ],
          colors: [ "#DC3545", "#17A2B8", "#FFC107" ],
          resize: true
        },
        lineChart: {
          data: {
            labels: ['9:00AM', '12:00AM', '3:00PM', '6:00PM', '9:00PM', '12:00PM', '3:00AM', '6:00AM'],
                        series: [
                          [{meta: 'description', value: 287}, {meta: 'description', value: 385}, {meta: 'description', value: 490}, {meta: 'description', value: 492}, {meta: 'description', value: 554}, {meta: 'description', value: 586}, {meta: 'description', value: 698}, {meta: 'description', value: 695}],
                          [67, 152, 143, 240, 287, 335, 435, 437],
                          [23, 113, 67, 108, 190, 239, 307, 308]
                        ]
          },
          options: {
            low: 0,
            high: 800,
            showArea: false,
            height: '245px',
            axisX: {
              showGrid: false
            },
            lineSmooth: true,
            showLine: true,
            showPoint: true,
            fullWidth: true,
            chartPadding: {
              right: 50
            }
           
          },
          responsiveOptions: [
            ['screen and (max-width: 640px)', {
              axisX: {
                labelInterpolationFnc (value) {
                  return value[0]
                }
              }
            }]
          ]
        },
        barChart: {
          data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            series: [
              [542, 443, 320, 780, 553, 453, 326, 434, 568, 610, 756, 895],
              [412, 243, 280, 580, 453, 353, 300, 364, 368, 410, 636, 695]
            ]
          },
          options: {
            seriesBarDistance: 10,
            axisX: {
              showGrid: false
            },
            height: '245px',
          },
          responsiveOptions: [
            ['screen and (max-width: 640px)', {
              seriesBarDistance: 5,
              axisX: {
                labelInterpolationFnc (value) {
                  return value[0]
                }
              }
            }]
          ]
        },
        tableData: {
          data: [
            {title: 'Sign contract for "What are conference organizers afraid of?"', checked: false},
            {title: 'Lines From Great Russian Literature? Or E-mails From My Boss?', checked: true},
            {
              title: 'Flooded: One year later, assessing what was lost and what was found when a ravaging rain swept through metro Detroit',
              checked: true
            },
            {title: 'Create 4 Invisible User Experiences you Never Knew About', checked: false},
            {title: 'Read "Following makes Medium better"', checked: false},
            {title: 'Unfollow 5 enemies from twitter', checked: false}
          ]
        }
      }
    },
    mounted() {
      this.loadStatisctics()
    },
    methods: {
        loadStatisctics() {
            this.$http.get('statistics')
                .then((response) => {
                    
                    this.lineData.data = response.data.year_visits
                    this.loadLine = false
                    this.areaData.data = response.data.month_visits
                    this.loadArea = false
                    if (response.data.ga) {
                      this.ga = response.data.ga
                    }else{
                      this.dem = true
                    }
                    
                }).catch(({ response }) => {
                    
                    })
        }
    }
  };
</script>

<style >

    .fulfilling-bouncing-circle-spinner, .half-circle-spinner {
     display: inline-block !important;
    }

    
</style>