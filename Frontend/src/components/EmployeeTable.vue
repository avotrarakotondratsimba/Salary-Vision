<template>
  <table>
    <thead>
      <tr>
        <th>Noms</th>
        <th>Salaire</th>
        <th>Observation</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="employee in employees" :key="employee.numEmp">
        <td>{{ employee.nom }}</td>
        <td>{{ formatSalary(employee.salaire) }} Ar</td>
        <td>{{ observation(employee.salaire) }}</td>
        <td id="action">
          <div @click="openModal(employee)"><img src="/editer.png" alt="éditer"></div>
          <div @click="deleteEmployee(employee.numEmp)"><img src="/supprimer (2).png" alt="éditer"></div>
        </td>
      </tr>
      <tr>
        <td><b>Salaire minimal</b></td>
        <td>{{ minSalary }} Ar</td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>Salaire maximal</b></td>
        <td>{{ maxSalary }} Ar</td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><b>Total</b></td>
        <td>{{ totalSalaries }} Ar</td>
        <td></td>
        <td></td>
      </tr>
    </tbody>
  </table>
  <update-table :modal-visibility="open" @update:modalVisibility="updateModalVisibility"  ref="updateTable"/>
  <!-- <insert-employee/> -->
</template>

<script>
import axios from 'axios';
import UpdateTable from './UpdateTable.vue'
import InsertEmployee from './InsertEmployee.vue'
import { mapActions } from 'vuex';

export default {
  emits: ['updateChart'],

  components: {
    'update-table': UpdateTable,
    'insert-employee': InsertEmployee
  },

  data() {
    return {
      employees: [],
      open: false,
      minSalar: null,
      maxSalar: null,
      totalSalar: null,
    }
  },

  mounted() {
    this.fetchEmployees();
  },

  methods: {
    ...mapActions(['refreshApp']),

    formatSalary(salary) {
      if (salary) {
        return salary.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
      }
      return "";
    },

    async fetchEmployees() {
      try {
        const response = await axios.get('http://localhost/Vue_Project/api.php?action=getAll');
        this.employees = response.data;    
      } catch (error) {
        console.error('Erreur lors de la récupération des employés:', error);
      }
    },

    openModal(employee) {
      this.open = true;
      this.$refs.updateTable.openModalWithData(employee);
    },


    updateModalVisibility(isVisible) {
      this.open = isVisible;
    },

    observation(salaire) {
      if(salaire) {
        if(salaire < 1000) {
          return 'Médiocre';
        } else if(salaire <= 5000) {
          return 'Moyen';
        } else {
          return 'Grand';
        }
      }
    },

    async deleteEmployee(id) {
      if (confirm("Êtes-vous sûr de vouloir supprimer cet employé ?")) {
        try {
          const response = await axios.delete(`http://localhost/Vue_Project/api.php?action=delete&id=${id}`);
          
          // Vérifiez la réponse de l'API pour confirmer la suppression
          if (response.data.message === 'Employé supprimé avec succès') {
            alert('Employé supprimé avec succès');
            
            // Rafraîchissez la liste des employés après la suppression
            this.refreshApp();
          } else {
            alert('Erreur lors de la suppression de l\'employé');
          }
        } catch (error) {
          console.error('Erreur lors de la suppression de l\'employé:', error);
          alert('Erreur lors de la suppression de l\'employé');
        }
      }
    }

  },

  computed: {
    minSalary() {
      if (this.employees.length === 0) this.minSalar = 0;  
      else this.minSalar = this.employees.reduce((min, emp) => Number(emp.salaire) < min ? emp.salaire : min, Number(this.employees[0].salaire));  
      return this.formatSalary(this.minSalar);
    },
    maxSalary() {
      if (this.employees.length === 0) this.maxSalar = 0;
      else this.maxSalar = this.employees.reduce((max, emp) => Number(emp.salaire) > max ? emp.salaire : max, Number(this.employees[0].salaire));
      return this.formatSalary(this.maxSalar)

    },
    totalSalaries() {
      if (this.employees.length === 0) this.totalSalar = 0;
      else this.totalSalar = this.employees.reduce((total, emp) => total + parseInt(emp.salaire), 0);
      return this.formatSalary(this.totalSalar)
    }
  },
}
</script>

<style scoped>
table {
  text-align: center;
  width: 80%;
  margin-left: 10%;
}

th:first-child {
    border-top-left-radius: 13px; /* Coin supérieur gauche */
}

th:last-child {
    border-top-right-radius: 13px; /* Coin supérieur droit */
}

table, th {
    padding: 1rem;
}

table td {
  padding: 0.5rem;
}

tbody tr:hover, tr:nth-child(odd):hover, tr:nth-child(even):hover{
    background-color: rgba(128, 128, 128, 0.116);
}

tbody tr:nth-child(odd) {
    /* background-color: #00000007; */
    background-color: #e1ecf059;
}

tbody tr:nth-child(even) {
    /* background-color: #00000007; */
    background-color: #fdfdfddc
}

th {
  background-color: #fdfdfddc
}

#action {
  display: flex;
  transform: translateX(19px);
}

#action > div {
  width: 37px;
  height: 37px;
  border-radius: 50%
}

#action > div:hover {
  background-color: #fff;
}

#action > div > img {
  width: 15px;
  height: 15px
}
</style>
