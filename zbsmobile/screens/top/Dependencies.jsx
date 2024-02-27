import { View, Text, FlatList } from 'react-native'
import React,{useEffect,useState} from 'react'
import DependentsTile from '../../components/Reusable/DependentsTile';
import { HeightSpacer,ErrorAlert } from '../../components';
import useAxiosFetch from '../../hooks/use-axios';
import MyActivityIndicator from '../../components/Reusable/MyActivityIndicator';
import { COLORS,SIZES } from '../../constants/theme';

const Dependencies = () => {
 
  const [dependents,setDependents] = useState([]);

  const {isLoading:isLoadingDependents,isError:isErrorDependents,data:dataDependents,statusCode:statusCodeDependents} = useAxiosFetch('getdependents');

  useEffect(() => {
 
    if(dataDependents && statusCodeDependents===200)
    {
       setDependents(dataDependents.dependents);      
    }
 },[dataDependents]);

 if(isLoadingDependents)
 {
    return (<MyActivityIndicator/>)
 }
 if(isErrorDependents)
 {
    return (<ErrorAlert message={`${dataDependents.message}`} onClose={()=>{}}/>)
 }

 
  return (
    <View style={{margin:20}}>
        {dependents.length<1 &&
 <Text style={{textAlign:'center',color:COLORS.green}}>No Dependents</Text>
 }
    <FlatList
data={dependents}
showsVerticalScrollIndicator={false}
keyExtractor={(item) => item.dependency_id}
renderItem={({item}) => (
   <View style={{ marginHorizontal:12,marginBottom:10}}>
  <DependentsTile item={item} />
  </View>
)}
 />
    </View>
  )
}

export default Dependencies