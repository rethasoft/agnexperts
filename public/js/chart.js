class MyChart {
    constructor(chartElementIds, dataUrls) {
        this.chartElements = chartElementIds.map(id => document.getElementById(id));
        this.dataUrls = dataUrls;
        this.chartInstances = [];
        if (!this.chartElements.every(Boolean)) {
            throw new Error('One or more chart elements not found.');
        }
    }

    async fetchData(url) {
        try {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error('Failed to fetch data');
            }
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error fetching data: ', error);
        }
    }


    getOptions() {
        return {
            type: 'bar',
            data: {
                labels: [],
                datasets: []
            },
            options: {
                barPercentage: 0.5,
                barThickness: 30,
                responsive: true,
                maintainAspectRatio: false,
                aspectRatio: 2,
                scales: {
                    x: {
                        stacked: true
                    },
                    y: {
                        beginAtZero: true,
                        stacked: true
                    }
                },
                elements: {
                    bar: {
                        borderRadius: 5,
                        borderWidth: 1,
                    }
                }
            }
        };
    }

    generateMonthLabels() {
        const months = [
            'januari', 'februari', 'maart', 'april', 'mei', 'juni',
            'juli', 'augustus', 'september', 'oktober', 'november', 'december'
        ];
        const year = '2024';
        return months.map(month => `${month}`);
    }


    async updateChart() {
        const responseData = await Promise.all(this.dataUrls.map(url => this.fetchData(url)));

        responseData.forEach((response, index) => {
            const element = this.chartElements[index];
            const chartOptions = this.getOptions();

            if (!element || !response) return; // Skip if chart element or response is invalid

            chartOptions.data.labels = this.generateMonthLabels();
            chartOptions.data.datasets = response.datasets || [];

            // Destroy existing chart instance if it exists
            if (this.chartInstances[index]) {
                this.chartInstances[index].destroy();
            }

            // Create a new Chart instance
            this.chartInstances[index] = new Chart(element, chartOptions);

            element.style.minHeight = '300px';
        });
    }

    initialize() {
        this.updateChart();
    }

}

// Usage:
try {
    const chartIds = ['mychart', 'mychart-2']; // IDs of chart elements
    const dataUrls = ['/app/tenant/ajax/getSales/yearly', '/app/tenant/ajax/getSalesByPartner/yearly']; // URLs for fetching data

    const myChart = new MyChart(chartIds, dataUrls);
    myChart.initialize();
} catch (error) {
    console.error(error.message);
}
