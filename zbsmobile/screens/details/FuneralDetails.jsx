import { View,FlatList } from 'react-native'
import React,{useState,useEffect} from 'react'
import { SafeAreaView } from 'react-native-safe-area-context';
import reusable from '../../components/Reusable/reusable.style';
import styles from './details.style';
import { COLORS,SIZES } from '../../constants/theme';
import AppBar from '../../components/Reusable/AppBar'
import { HeightSpacer,ReusableText,ErrorAlert } from '../../components';
import FuneralsTile from '../../components/Reusable/FuneralsTile';
import { useRoute } from '@react-navigation/native';
import useAxiosFetch from '../../hooks/use-axios';
import MyActivityIndicator from '../../components/Reusable/MyActivityIndicator';

const FuneralDetails = () => {
  const [funeralName,setFuneralName] = useState('');
  const [deceased,setDeceased] = useState([]);

  const route = useRoute();
  const { funeral_id} = route.params;
  const {isLoading:isLoadingDeceased,isError:isErrorDeceased,data:dataDeceased,statusCode:statusCodeDeceased} = useAxiosFetch(`getdeceased/${funeral_id}`);
  
  useEffect(() => {
 
    if(dataDeceased && statusCodeDeceased===200)
    {
      setFuneralName(dataDeceased.funeral_name);
      setDeceased(dataDeceased.deceased);
    }
 },[dataDeceased]);

 if(isLoadingDeceased)
{
   return (<MyActivityIndicator/>)
}
if(isErrorDeceased)
{
   return (<ErrorAlert message={`${dataDeceased.message}`} onClose={()=>{}}/>)
}

  return (
   <SafeAreaView style={reusable.container}>
    <View style={{height:50}}>
    <AppBar title={'Search'} color={COLORS.white}  icon={'home'} color1={COLORS.white}/>
    </View>
    <HeightSpacer height={20}/>
<ReusableText
text={funeralName}
family={'medium'}
size={SIZES.large}
color={COLORS.green}
/>
<HeightSpacer height={20}/>

<FlatList
data={deceased}
keyExtractor={(item) => item.id}
showsVerticalScrollIndicator={false}
renderItem={({item}) => (
  <View style={styles.tile}>
    <FuneralsTile item={item} />
  </View>
)}
 />
   </SafeAreaView>
  )
}

export default FuneralDetails