<template>
    <Pie :data="chartData" v-if="loaded" />
</template>
    
  <script>
  import { Chart as ChartJS, Tooltip, Legend, ArcElement } from 'chart.js'
  import { Pie } from 'vue-chartjs'
  import axios from 'axios'
  // import UpdateTable from './InsertEmployee.vue'
  import InsertEmployee from './InsertEmployee.vue'
  
  ChartJS.register(Tooltip, Legend, ArcElement)
    
  export default {
    name: 'PieChart',
    components: {
        Pie,
        'insert-employee': InsertEmployee
    },
      
    data () {
      return {
        test: false,
        loaded: false,
        minimal: null,
        maximal: null,
        total: null,
        chartData: null,
        employees: [],
        never: false
      }
    },
  
    mounted() {
      this.loadData();
    },
  
    methods: {
    async loadData() {
      try {
        await this.fetchEmployees();
        this.minSalary();
        this.maxSalary();
        this.totalSalaries();

        this.updateChartData();  // Mettez à jour les données du graphe ici

        this.loaded = true;

      } catch (e) {
        console.error(e);
      }
    },

    updateChartData() {
      this.chartData = {
        labels: ['Minimal', 'Maximal', 'Total'],
        datasets: [
          {
            backgroundColor: ['#41B883', '#E46651', '#00D8FF'],
            data: [this.minimal, this.maximal, this.total]
          }
        ]
      };
      console.log(this.minimal, this.maximal, this.total)
    },
  
      async fetchEmployees() {
        try {
          const response = await axios.get('http://localhost/Vue_Project/api.php?action=getAll');
          this.employees = response.data;     
        } catch (error) {
          console.error('Erreur lors de la récupération des employés:', error);
        }
      },
  
      minSalary() {
        if (this.employees.length === 0) this.minimal = 0;
        this.minimal = this.employees.reduce((min, emp) => Number(emp.salaire) < min ? emp.salaire : min, Number(this.employees[0].salaire));
      },
  
      maxSalary() {
        if (this.employees.length === 0) this.maximal = 0;
        this.maximal = this.employees.reduce((max, emp) => Number(emp.salaire) > max ? emp.salaire : max, Number(this.employees[0].salaire));
      },
  
      totalSalaries() {
        if (this.employees.length === 0) this.total = 0;
        this.total = this.employees.reduce((total, emp) => total + parseInt(emp.salaire), 0);
      }
    }
  }
  </script>
  

<!-- 
   <template>
    <div>
      <canvas ref="canvas"></canvas>
      <insert-employee @chartUpdated="destroyChart" />
    </div>
  </template>
  
  <script>
  import { Chart as ChartJS, Tooltip, Legend, ArcElement } from 'chart.js'
  import { Pie } from 'vue-chartjs'
  import axios from 'axios'
  import InsertEmployee from './InsertEmployee.vue'
  
  ChartJS.register(Tooltip, Legend, ArcElement)
  
  export default {
  name: 'PieChart',
  components: {
    Pie,
    'insert-employee': InsertEmployee
  },
  data() {
    return {
      loaded: false,
      minimal: null,
      maximal: null,
      total: null,
      chartData: null,
      employees: [],
      chartInstance: null
    };
  },
  mounted() {
    this.loadData();
  },
  methods: {
    async loadData() {
      try {
        await this.fetchEmployees();
        this.minSalary();
        this.maxSalary();
        this.totalSalaries();

        this.updateChartData();
        
        if (this.chartInstance) {
          this.chartInstance.update();
        } else {
          this.initChart();
        }

        this.loaded = true;

      } catch (e) {
        console.error(e);
      }
    },
    updateChartData() {
      this.chartData = {
        labels: ['Minimal', 'Maximal', 'Total'],
        datasets: [
          {
            backgroundColor: ['#41B883', '#E46651', '#00D8FF'],
            data: [this.minimal, this.maximal, this.total]
          }
        ]
      };
    },
    initChart() {
      const ctx = this.$refs.canvas.getContext('2d');
      this.chartInstance = new ChartJS(ctx, {
        type: 'pie',
        data: this.chartData,
        options: {
          // Add your chart options here if necessary
        }
      });
    },
    destroyChart() {
      if (this.chartInstance) {
        this.chartInstance.destroy();
        this.chartInstance = null;
      }
    },
    async fetchEmployees() {
      try {
        const response = await axios.get('http://localhost/Vue_Project/api.php?action=getAll');
        this.employees = response.data;
      } catch (error) {
        console.error('Erreur lors de la récupération des employés:', error);
      }
    },
    minSalary() {
      if (this.employees.length === 0) this.minimal = 0;
      this.minimal = this.employees.reduce((min, emp) => Number(emp.salaire) < min ? emp.salaire : min, Number(this.employees[0].salaire));
    },
    maxSalary() {
      if (this.employees.length === 0) this.maximal = 0;
      this.maximal = this.employees.reduce((max, emp) => Number(emp.salaire) > max ? emp.salaire : max, Number(this.employees[0].salaire));
    },
    totalSalaries() {
      if (this.employees.length === 0) this.total = 0;
      this.total = this.employees.reduce((total, emp) => total + parseInt(emp.salaire), 0);
    }
  }
};
</script>

<style scoped>
/* Your CSS styles here */
</style> -->