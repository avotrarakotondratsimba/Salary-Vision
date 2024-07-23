<template>
  <button @click="isModalOpen = true" id="add">
    <img src="/ajouter-un-utilisateur.png" alt="ajouter">
  </button>
    <div v-if="isModalOpen" class="modal-overlay">
      <div class="custom-modal">
        <div class="modal-header">
          <span class="modal-title">Ajouter un employé</span>
          <button @click="closeModal" class="close-button">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                  <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
              </svg>
          </button>
        </div>
        <div class="modal-content">
          <form>
            <div class="form-group">
              <label for="numero">Numéro:</label>
              <input type="text" id="numero" class="input-field" placeholder="Entrez le numéro" v-model="employeeNumber" readonly>
            </div>
            <div class="form-group">
              <label for="nom">Nom:</label>
              <input type="text" id="nom" @input="toggleButton" class="input-field" placeholder="Entrez le nom" v-model="employeeName">
            </div>
            <div class="form-group">
              <label for="salaire">Salaire (en Ar):</label>
              <input type="number" id="salaire" @input="toggleButton" class="input-field" placeholder="Entrez le salaire" v-model="employeeSalary">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button @click="closeModal" class="close-button2">Annuler</button>
          <button @click="newEmployee(this.employeeName, this.employeeSalary)" :disabled="status" class="save-button">Enregistrer</button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
    import axios from 'axios';
    import { mapActions } from 'vuex';
  
    export default {
      data() {
        return {
          isModalOpen: false,
          employeeNumber: '',
          employeeName: '',
          employeeSalary: '',
          status: true
        }
      },

      mounted() {
        this.findId()
      },
  
      methods: {
        ...mapActions(['refreshApp']),

        closeModal() {
          this.isModalOpen = false;
          // this.$emit('update:modalVisibility', this.isModalOpen);
        },

        async findId() {
          const response = await axios.put(`http://localhost/Vue_Project/api.php?action=getID`);
          console.log(response.last_id)
          const calc = Number(response.data.last_id) + 1
          this.employeeNumber = 'E' + calc
        },

        async newEmployee(name, salary) {
          const data = {
            nom: name,
            salaire: parseInt(salary)
          };
          console.log(data)
          try {
            // Effectuer la requête PUT
            const response = await axios.put(`http://localhost/Vue_Project/api.php?action=create`, data);
            // Fermer le modal après la mise à jour
            // Afficher la réponse dans la console
            console.log(response.data.message);
            this.refreshApp();
            this.closeModal();
          } catch (error) {
            console.error('Erreur lors de la mise à jour de l\'employé:', error);
          }
        },

        toggleButton() {
          if(this.employeeName != '' && this.employeeSalary != '') {
            this.status = false
          } else {
            this.status = true
          }
        }
      }
    };
  </script>
  
  <style scoped>
  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .custom-modal {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
    width: 80%;
    max-width: 500px;
    /* position: absolute; */
    /* margin-bottom: 350px; */
  }
  
  .modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    border-bottom: 1px solid #ccc;
  }
  
  .modal-title {
    font-size: 20px;
  }
  
  .close-button {
      background: none;
      border: none
  }
  
  .close-button > svg{
    background-color: white;
    cursor: pointer;
    width: 35px;
    height: 35px;
  }
  
  .close-button2 {
    padding: 10px 20px;
    color: #fff;
    cursor: pointer;
    border: none;
    border-radius: 4px;
    background-color: rgb(77, 77, 77);
  }
  
  .modal-content {
    padding: 15px;
  }
  
  .input-field {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }
  
  .modal-footer {
    display: flex;
    justify-content: flex-end;
    padding: 15px;
    border-top: 1px solid #ccc;
  }
  
  .open-button, .save-button {
    padding: 10px 20px;
    margin-left: 10px;
    cursor: pointer;
    border: none;
    border-radius: 4px;
    color: #fff;
    background-color: #007bff;
  }

  #add{
    width: 30px;
    height: 30px;
    border: none;
    background-color: white;
  }

  #add > img {
    width: 30px;
    height: 30px;
  }
  </style>
  