import Vue from 'vue'
import VueRouter from 'vue-router'

import Login from '../views/Auth/Login.vue'
import Register from '../views/Auth/Register.vue'
import ShipmentIndex from '../views/Shipment/Index.vue'
import ShipmentShow from '../views/Shipment/Show.vue'
import ShipmentForm from '../views/Shipment/Form.vue'
import NotFound from '../views/NotFound.vue'

Vue.use(VueRouter)

const router = new VueRouter({
	mode: 'history',
	routes: [
		{ path: '/', component: ShipmentIndex },
		{ path: '/shipments/create', component: ShipmentForm, meta: { mode: 'create' }},
		{ path: '/shipments/:id/edit', component: ShipmentForm, meta: { mode: 'edit' }},
		{ path: '/shipments/:id', component: ShipmentShow },
		{ path: '/register', component: Register },
		{ path: '/login', component: Login },
		{ path: '/not-found', component: NotFound },
		{ path: '*', component: NotFound }
	]
})

export default router
