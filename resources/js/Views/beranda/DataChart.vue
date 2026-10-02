<template>
    <Bar :chart-options="chartOptions" :chart-data="chartData" />
</template>

<script>
import { defineComponent, h, PropType } from 'vue'

import Chart from 'chart.js/auto';
import { Bar, Line } from 'vue-chartjs'
import moment from 'moment';

export default defineComponent({
    name: 'DataChart',
    components: {
        Bar
    },
    props: {
        chartData: {
            type: Object,
            default: {}
        },
        title: {
            type: String,
            default: 'Data Pelanggaran'
        },
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
            default: () => { }
        },
        plugins: {
            type: Array,
            default: () => []
        }
    },
    setup(props) {
        const chartOptions = {
            //indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            interaction: {
                mode: 'index',
            },
            layout: {
                padding: 10
            },
            plugins: {
                tooltip: {
                    itemSort: function (a, b) {
                        return b.raw - a.raw;
                    }
                },
                legend: {
                    display: false,
                    // position: 'bottom',
                    // onClick: Chart.defaults.plugins.legend.onClick,
                    // labels: {
                    //     usePointStyle: true,
                    //     color: 'black',
                    //     generateLabels: chart => {
                    //         return chart.data.datasets.map((v, i) => {
                    //             if (v.label === 'Pelanggaran') {
                    //                 return {
                    //                     text: v.label,
                    //                     datasetIndex: i,
                    //                     pointStyle: 'rect',
                    //                     fillStyle: v.backgroundColor,
                    //                     strokeStyle: v.borderColor,
                    //                 };
                    //             } else {
                    //                 return {
                    //                     text: v.label,
                    //                     datasetIndex: i,
                    //                     pointStyle: 'line',
                    //                     fillStyle: v.borderColor,
                    //                     strokeStyle: v.borderColor
                    //                 };
                    //             }
                    //         })

                    //     }
                    // }
                },
                title: {
                    display: true,
                    text: props.title,
                    position: 'top',
                    align: 'center',
                    font: {
                        size: 15,
                        weight: 'bold'
                    },
                    padding: {
                        top: 10,
                        bottom: 30
                    },
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        display: false,
                    },
                    border: {
                        color: 'black',
                        width: 10
                    },
                },
                y: {
                    grid: {
                        display: false
                    },
                    border: {
                        display: true,
                        color: 'black',
                        width: 10
                    },
                },
            }
        }
        return () =>
            h(Bar, {
                chartData: props.chartData,
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

</script>
