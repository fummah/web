import { View, FlatList } from 'react-native'
import React,{useState,useEffect} from 'react';
import AdminsTile from '../../components/Reusable/AdminsTile';
import { HeightSpacer,ErrorAlert } from '../../components';
import useAxiosFetch from '../../hooks/use-axios';
import MyActivityIndicator from '../../components/Reusable/MyActivityIndicator';
import { COLORS,SIZES } from '../../constants/theme';

const Admins = () => {
  const [admins,setAdmins] = useState([]);

  const {isLoading:isLoadingAdmins,isError:isErrorAdmins,data:dataAdmins,statusCode:statusCodeAdmins} = useAxiosFetch('getadmins');

  useEffect(() => {
 
    if(dataAdmins && statusCodeAdmins===200)
    {
       setAdmins(dataAdmins.admins);      
    }
 },[dataAdmins]);

 if(isLoadingAdmins)
 {
    return (<MyActivityIndicator/>)
 }
 if(isErrorAdmins)
 {
    return (<ErrorAlert message={`${dataAdmins.message}`} onClose={()=>{}}/>)
 }

  return (
    <View style={{margin:20}}>
    <FlatList
data={admins}
showsVerticalScrollIndicator={false}
keyExtractor={(item) => item.user_id}
renderItem={({item}) => (
   <View style={{ marginHorizontal:12,marginBottom:10}}>
  <AdminsTile item={item} />
  </View>
)}
 />
    </View>
  )
}

export default Admins