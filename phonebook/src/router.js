import Vue from 'vue'
import Router from 'vue-router'

import List from './components/List.vue'
import Create from './components/Create.vue'
import Edit from './components/Edit.vue'

Vue.use(Router)

export default new Router({
	routes: [
		{path: '/', component: List, name: 'list'},
		{path: '/create', component: Create, name: 'create'},
		{path: '/edit/:id', component: Edit, name: 'edit'},
	]
})