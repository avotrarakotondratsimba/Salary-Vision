import { createApp } from 'vue'
import App from './App.vue'
import store from './store.js'
import EmployeeTable from './components/EmployeeTable.vue'
import InsertEmployee from './components/InsertEmployee.vue';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';


const app = createApp(App)
app.use(store)
app.component('employee-table', EmployeeTable)
app.component('insert-employee', InsertEmployee)
app.mount('#app')
