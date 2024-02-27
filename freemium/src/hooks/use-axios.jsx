import axios from 'axios';
import {useEffect, useReducer} from 'react';

export default function useAxiosFetch(url,request_method="GET",postData={}){

	const [state1, dispatch] = useReducer(
(state,action) => {
	switch(action.type){
	case 'INIT':
		return {...state,isLoading:true, isError:false};
	case 'SUCCESS':
		return {...state,isLoading:false,isError:false,data:action.payload,statusCode:action.code};
	case 'ERROR':
		return {...state,isLoading:false,isError:true,data:action.payload,statusCode:action.code};
	default:
      return {...state,isLoading:false,isError:false,data:null,statusCode:null};
	}
},
{
	isLoading:false,
	isError:false,
	data:null,
	statusCode:null,
},
		);

const eff_arr = request_method==="POST"?[url,postData]:[url];
	useEffect(()=>{
if(!url){
	return;
}

if(Object.keys(postData).length === 0 && request_method==="POST"){
	return;
}



const fetch = async () =>{
	const main_url = import.meta.env.VITE_MYAPI;
	dispatch({type:'INIT'});
try{
	const token = localStorage.getItem('ACCEESS_GRANTED');
	const result = await axios(`${main_url}/${url}`, {
        method: request_method,
         data: postData,
         params: postData,
        headers: {
			'Authorization': `Bearer ${token}`
          }});
dispatch({type:'SUCCESS',payload:result.data,code:200});
}
catch(error)
{
dispatch({type:'ERROR',payload:error.response.data,code:error.response.status});
}
};
fetch();
// eslint-disable-next-line react-hooks/exhaustive-deps
	},eff_arr);

	return state1;
}