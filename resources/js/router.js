import Vue from "vue";
import Router from "vue-router";
Vue.use(Router);
import newvue from "./components/pages/NewVueComponent";
import hooks from "./components/pages/basic/hooks.vue";
import methods from "./components/pages/basic/methods.vue";

const routes = [

    // Projrcts routes...


    
    // basic tutorials routes
    {
        path: "/new-vue",
        component: newvue,
    },

    //  Vue hooks
    {
        path: "/hooks",
        component: hooks,
    },

    //  more basic
    {
        path: "/methods",
        component: methods,
    }
];

export default new Router({
    mode: "history",
    routes
});
