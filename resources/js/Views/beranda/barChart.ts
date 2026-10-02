import { defineComponent, h, PropType } from 'vue'

import { Bar, Line } from 'vue-chartjs'

import {
  Chart,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
  Plugin
} from 'chart.js'

Chart.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

export default defineComponent({
  name: 'BarChart',
  components: {
    Bar
  },
  props: {
    chartId: {
      type: String,
      default: 'bar-chart'
    },
    width: {
      type: Number,
      default: 400
    },
    height: {
      type: Number,
      default: 400
    },
    cssClasses: {
      default: '',
      type: String
    },
    styles: {
      type: Object,
      default: () => {}
    },
    plugins: {
      type: Array,
      default: () => []
    }
  },
  setup(props) {
    const chartData = {
      labels: ['jan', 'feb', 'mar', 'apr', 'mei'],
      datasets: [
        {
                        label: 'WIM',
                        data: [1, 5, 4, 8, 2],
                        backgroundColor: 'rgb(255,255,255)',
                        borderColor: 'rgb(75, 192, 192)',
                        borderWidth: 2,
                        pointStyle: 'circle',
                        pointRadius: 5,
                        pointHoverRadius: 10,
                        type: 'line'
                    },
                    {
                        label: 'LHR',
                        data: [3, 5, 2, 12, 6],
                        backgroundColor: 'rgb(255,255,255)',
                        borderColor: 'rgb(255, 205, 86)',
                        borderWidth: 2,
                        pointStyle: 'circle',
                        pointRadius: 5,
                        pointHoverRadius: 10,
                        type: 'line'
                    },
                    {
                        label: 'Pelanggaran',
                        data: [4, 10, 6, 20, 8],
                        backgroundColor: 'rgb(54, 162, 235)',
                        borderColor: 'blue',
                        borderWidth: 1,
                        type: 'bar'
                    },
      ]
    }

    const chartOptions = {
      responsive: true,
      maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            onClick: Chart.defaults.plugins.legend.onClick,
                            labels: {
                                usePointStyle: true,
                                color: 'red',
                                generateLabels: chart => {
                                    return chart.data.datasets.map((v, i) => {
                                        if (v.label === 'Pelanggaran') {
                                            return {
                                                text: v.label,
                                                datasetIndex: i,
                                                pointStyle: 'rect',
                                                fillStyle: v.backgroundColor,
                                                strokeStyle: v.borderColor
                                            };
                                        } else {
                                            return {
                                                text: v.label,
                                                datasetIndex: i,
                                                pointStyle: 'line',
                                                fillStyle: v.borderColor,
                                                strokeStyle: v.borderColor
                                            };
                                        }
                                    })

                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Data Pelanggaran Per Tahun 2022',
                            font: {
                                size: 16,
                                weight: 'bold'
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                            },
                            border: {
                                color: 'black',
                                width: 1
                            },
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: false
                            },
                            border: {
                                color: 'black',
                                width: 1
                            }
                        }
                    }
    }

    return () =>
      h(Bar, {
        chartData,
        chartOptions,
        chartId: props.chartId,
        width: props.width,
        height: props.height,
        cssClasses: props.cssClasses,
        styles: props.styles,
        plugins: props.plugins
      })
  }
})
