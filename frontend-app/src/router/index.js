import { createRouter, createWebHistory } from 'vue-router'

const routes = [
	{
		path: '/',
		component: () => import('../views/Home.vue')
	},
	{
		path: '/favoritos',
		component: () => import('../views/Favorites.vue')
	}
]

const router = createRouter({
	history: createWebHistory(),
	routes
})

export default router