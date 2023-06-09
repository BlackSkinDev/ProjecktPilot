

import {createRouter, createWebHistory}  from 'vue-router'


const routes = [

    {
        path:'/test',
        name:"test",
        component:()=>import('../components/Test.vue'),
    },


    {
        path:'/',
        name:"homepage",
        component:()=>import('../pages/Index.vue'),
        meta: {
            redirectIfAuthenticated: true
        }
    },

    {
        path:'/signin',
        name:"signin",
        component:()=>import('../pages/auth/login.vue'),
        meta: {
            redirectIfAuthenticated: true
        }
    },

    {
        path:'/email/verify/:token',
        name:"verify-email",
        component:()=>import('../pages/auth/verify-email.vue'),
        meta: {
            redirectIfAuthenticated: true
        }
    },

    {
        path:'/password-reset/request',
        name:"password-reset-request",
        component:()=>import('../pages/auth/password-reset.vue'),
        meta: {
            redirectIfAuthenticated: true
        }
    },

    {
        path:'/dashboard/your-work',
        name:"dashboard",
        component:()=>import('../pages/work/Index.vue'),
        meta: {
            requiresAuth: true
        }
    },

    {
        path:'/dashboard/company/create',
        name:"create-company",
        component:()=>import('../pages/company/Create.vue'),
        meta: {
            requiresAuth: true
        }
    },

    {
        path:'/dashboard/company',
        name:"company",
        component:()=>import('../pages/company/Show.vue'),
        meta: {
            requiresAuth: true
        }
    },

    {
        path:'/dashboard/account',
        name:"account",
        component:()=>import('../pages/account/Show.vue'),
        meta: {
            requiresAuth: true
        }
    },


    {
        path:'/dashboard/settings',
        name:"settings",
        component:()=>import('../pages/settings/Index.vue'),
        meta: {
            requiresAuth: true
        }
    },



    {
        path:'/dashboard/people',
        name:"people",
        component:()=>import('../pages/people/Index.vue'),
        meta: {
            requiresAuth: true
        }
    },



    {
        path:'/dashboard/people/:id',
        name:"people-profile",
        component:()=>import('../pages/people/Show.vue'),
        meta: {
            requiresAuth: true
        }
    },


    {
        path:'/dashboard/projects',
        name:"projects",
        component:()=>import('../pages/project/Index.vue'),
        meta: {
            requiresAuth: true
        }
    },



]

const router = createRouter({
    history:createWebHistory(),
    routes
})

router.beforeEach(async (to, from, next) => {
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
    const redirectIfAuthenticated = to.matched.some(record => record.meta.redirectIfAuthenticated);
    const user_is_logged_in =  localStorage.getItem('logged_in')
    if (requiresAuth) {
        if (!user_is_logged_in) {
            next({ name: 'signin' });
        } else {
            next();
        }
    } else if (redirectIfAuthenticated) {
        if (user_is_logged_in) {
            next({ name: 'dashboard' });
        } else {
            next();
        }
    } else {
        next();
    }
});




export default router
