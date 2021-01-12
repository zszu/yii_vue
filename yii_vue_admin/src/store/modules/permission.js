import { constantRouterMap } from '@/router'

function hasPermission(roleRouers)
{
    let routerItems = [];

    roleRouers.forEach((val)=>{

        let item = getAsyncRouter(val);
        if(item){
            routerItems.push(item);
        }
    });
    return routerItems;
}


function getAsyncRouter(roleRouter)
{
	if(roleRouter['path'] && !roleRouter.children){

		var arr = roleRouter['path'].split('/');
		var index = arr[arr.length-1];
		let routerItems = {
	            path: roleRouter['path'],
	            component: getViews(roleRouter['path']),
	            meta: { title:roleRouter['name']},
	            index: index,
	            name: roleRouter['name'],
		};

		return routerItems;
	}
	return false;

}

function getViews(path) {
	if(path.substr(0,1)=='/')  path = path.substr(1);

  return resolve => {
    require.ensure([], () => {
      resolve(require(`../../`+path+`.vue`)) 
    })
  }
}

//添加默认的路由
function addDefaultRouter(roleRouterItems)
{
	let routerItems = [{
            path: '/',
            component: resolve => require(['../../pages/layout/home.vue'], resolve),
            meta: { title: '公共部分' },
            children:[],
	}];	
    let dashboard = {
        path: '/dashboard',
        component: resolve => require(['../../pages/dashboard.vue'], resolve),
        meta: { title: '系统首页' },
        index:'dashboard',
        name: '系统首页',
        icon: 'iconfont icon-fenlei '    	
    };
    roleRouterItems.push(dashboard);

	routerItems[0].children = roleRouterItems;

	let item404 = {
        path: '*',
        redirect: '../404'
    };
	routerItems.push(item404);
	return routerItems;
}


const permission = {
	 state: {
	    routers: constantRouterMap,
	    addRouters: []
	},
	mutations:{
	    SET_ROUTERS: (state, routers) => {

            let RouterItems = hasPermission(routers);
	    	//添加两个默认路由
	    	let roleRouterItems = addDefaultRouter(RouterItems);
	    	
	    	//动态添加的路由
	    	state.addRouters = roleRouterItems;

	      	//所有路由
	      	state.routers = constantRouterMap.concat(roleRouterItems);
	    },

	},
	actions: {
		GenerateRoutes({ commit }, data) {
			// console.log(data);
			commit('SET_ROUTERS', data);
		},
	},

}

export default permission;