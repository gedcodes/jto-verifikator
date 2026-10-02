<template>
    <Doughnut :chart-options="chartOptions" :chart-data="chartData" :plugins="plugins" :height="height"
        :width="width" />
</template>

<script>
import { defineComponent, h, PropType } from 'vue'

import Chart from 'chart.js/auto';
import { Bar, Line, Doughnut } from 'vue-chartjs'
import ChartDataLabel from 'chartjs-plugin-datalabels';
import moment from 'moment';

export default defineComponent({
    name: 'DougnutChart',
    components: {
        Doughnut,
    },
    props: {
        chartData: {
            type: Object,
            default: {}
        },
        chartId: {
            type: String,
            default: 'bar-chart'
        },
        width: {
            type: Number,
            default: 200
        },
        height: {
            type: Number,
            default: 200
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
            responsive: true,
            layout: {
                padding: 10
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'Persentase Perkategori Kepemilikan ' + moment().format('MMMM, YYYY'),
                    font: {
                        size: 16,
                        weight: 'bold'
                    },
                    padding: {
                        top: 10,
                        bottom: 30
                    },
                },
                cutout: '70%',
                tooltip: {
                    enabled: true,
                    callbacks: {
                        label: function (tooltipItem, data) {
                            var labels = tooltipItem.label;
                            var values = tooltipItem.dataset.data;
                            var value = tooltipItem.dataset.data[tooltipItem.dataIndex];
                            var total = 0;
                            for (var i in values) {
                                total += values[i];
                            }
                            var percentage = Math.round((value / total) * 100);
                            var label = (labels + " : " + value + ' (' + percentage + '%)');
                            return labels;
                        }
                    }
                },
                labels: {
                    render: 'percentage',
                    fontColor: ['green', 'white', 'red'],
                    precision: 2
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                },
                datalabels: {
                    backgroundColor: function (context) {
                        return context.dataset.backgroundColor;
                    },
                    borderColor: 'white',
                    borderRadius: 10,
                    borderWidth: 2,
                    color: 'white',
                    // display: function (context) {
                    //     var dataset = context.dataset;
                    //     console.log(context);
                    //     var count = dataset.data.length;
                    //     var value = dataset.data[context.dataIndex] + '%';
                    //     console.log(value)
                    //     return value;
                    // },
                    font: {
                        weight: 'bold'
                    },
                    //padding: 6,
                    formatter: function (value, context) {
                        return context.dataset.data[
                            context.dataIndex
                        ] + '%';
                        // return context.chart.data.labels[
                        //     context.dataIndex
                        // ];
                    },
                },
            },
        }
        return () =>
            h(Doughnut, {
                chartData: props.chartData,
                chartOptions,
                chartId: props.chartId,
                width: props.width,
                height: props.height,
                cssClasses: props.cssClasses,
                styles: props.styles,
                plugins: [ChartDataLabel],
            })
    }
})

</script>
