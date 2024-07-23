<template>
  <div class="container">
    <h1>Liste des employés</h1>
    <!-- <div class="add"><insert-employee/></div> -->
    <div class="tab">
      <div class="header">
        <input type="text" id="search" >
        <img src="/loupe.png" alt="rechercher" id="icone">
        <insert-employee/>
      </div>
      <employee-table/>
    </div> <br> <br>
    <div class="chart">
      <div>
        <pie-chart/>
      </div>
      <h4>Diagramme des salaires: minimal, maximal et total</h4>
    </div>
    
  </div>
</template>

<script>
  import PieChart from './components/PieChart.vue';
  import { mapState } from 'vuex';
  export default {
    components: {
      'pie-chart': PieChart,
    },

    computed: {
      ...mapState(['refreshApp']),
    },

    watch: {
    refreshApp(newVal) {
      if (newVal) {
        // Code pour rafraîchir ou recharger App.vue
        // Par exemple, vous pouvez utiliser une fonction pour réinitialiser l'état de l'application
        this.resetAppState();
        // Remettez la valeur à false après le rechargement
        this.$store.dispatch('refreshApp', false);
      }
    },
  },
  methods: {
    resetAppState() {
      // Réinitialisez l'état de votre application ici
      // Par exemple :
      window.location.reload();  // Recharge toute la page
      // Ou
      // Mettez à jour les données ou réinitialisez les composants nécessaires
    },
  },
  }
</script>

<style scoped>
.header {
  margin-bottom: 25px;
  margin-left: 10%;
  padding-top: 30px
}

#search {
  border: 1px solid rgba(115, 115, 247, 0.3);
  border-radius: 15px;
  padding-left: 30px;
}

#icone {
  width: 13px;
  height: 13px;
  transform: translateX(-205px);
  margin-bottom: 2px;
}

.tab {
  width: 100%;
  height: 500px;
  /* margin-top: 20px; */
  box-shadow: 0px 8px 16px 0px rgba(51, 50, 50, 0.2);
  /* background-color: rgba(128, 128, 128, 0.5); */
  border-radius: 20px;
  background-color: white;
  overflow: auto;
  /* border: 8px #0000ff42 solid; */
}

.container {
  padding-top: 50px;
} 

.chart {
  width: 50%;
  height: 400px;
  margin-left: 25%;
}

.chart > div {
  height: 350px;
  width: 60%;
  margin-left: 20%;
}

h1 {
  margin-left: 37%
}


</style>







