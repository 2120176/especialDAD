

const routes = [
  {
    path: '/',
    component: ()=> import('components/Dashboard/Layout/DashboardLayout.vue'),
    redirect: '/admin/overview'
  },
  {
    path: '/admin',
    component: ()=> import('components/Dashboard/Layout/DashboardLayout.vue'),
    redirect: '/admin/overview',
    children: [
      {
        path: 'overview',
        name: 'Overview',
        component: ()=> import('components/Dashboard/Views/Overview.vue')
      },
      {
        path: 'user',
        name: 'User',
        component: ()=> import('components/Dashboard/Views/UserProfile.vue')
      },
      {
        path: 'table-list',
        name: 'Table List',
        component: ()=> import('components/Dashboard/Views/TableList.vue')
      },
      {
        path: 'typography',
        name: 'Typography',
        component: ()=> import('components/Dashboard/Views/Typography.vue')
      },
      {
        path: 'icons',
        name: 'Icons',
        component: ()=> import('components/Dashboard/Views/Icons.vue')
      },
      {
        path: 'maps',
        name: 'Maps',
        component: ()=> import('components/Dashboard/Views/Maps.vue')
      },
      {
        path: 'notifications',
        name: 'Notifications',
        component: ()=> import('components/Dashboard/Views/Notifications.vue')
      }
    ]
  },
  { path: '*', component: ()=> import('components/GeneralViews/NotFoundPage.vue') }
]

/**
 * Asynchronously load view (Webpack Lazy loading compatible)
 * The specified component must be inside the Views folder
 * @param  {string} name  the filename (basename) of the view to load.
function view(name) {
   var res= require('../components/Dashboard/Views/' + name + '.vue');
   return res;
};**/

export default routes
