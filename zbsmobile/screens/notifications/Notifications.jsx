import { View, Text, FlatList, RefreshControl } from 'react-native'
import React,{useState,useEffect} from 'react'
import { SafeAreaView } from 'react-native-safe-area-context';
import reusable from '../../components/Reusable/reusable.style';
import styles from './notifications.style';
import { COLORS,SIZES } from '../../constants/theme';
import AppBar from '../../components/Reusable/AppBar'
import { HeightSpacer, ReusableText,ErrorAlert } from '../../components';
import NotificationTile from '../../components/Reusable/NotificationTile';
import useAxiosFetch from '../../hooks/use-axios';
import MyActivityIndicator from '../../components/Reusable/MyActivityIndicator';

const Notifications = () => {
  const [notifications,setNotifications] = useState([]);
  const [refreshing, setRefreshing] = useState(false);
const [refreshKey, setRefreshKey] = useState(0);

  const {isLoading:isLoadingNotifications,isError:isErrorNotifications,data:dataNotifications,statusCode:statusCodeNotifications} = useAxiosFetch(`getnotifications?refreshKey=${refreshKey}`);

  useEffect(() => {
 
    if(dataNotifications && statusCodeNotifications===200)
    {
      setNotifications(dataNotifications.notifications);  
      setRefreshing(false); 
    }
 },[dataNotifications]);

 if(isLoadingNotifications)
 {
    return (<MyActivityIndicator/>)
 }
 if(isErrorNotifications)
 {
    return (<ErrorAlert message={`${dataNotifications.message}`} onClose={()=>{}}/>)
 }
 const onRefresh = () => {
  setRefreshing(true); 
  setRefreshKey(prevKey => prevKey + 1);
};

  return (
   <SafeAreaView style={reusable.container}
   
    >
    
    <View style={{height:50}}>
    <AppBar title={'Notifications'} color={COLORS.white}  icon={'home'} color1={COLORS.white}/>
    </View>
<View style={styles.searcContainer}>
    <HeightSpacer height={20}/>
<ReusableText
text={'My Notifications'}
family={'medium'}
size={SIZES.large}
color={COLORS.green}
/>
<HeightSpacer height={20}/>

</View>

       
{notifications.length<1 &&
<Text style={{color:COLORS.green}}>No Notifications</Text>
}
 <FlatList
data={notifications}
keyExtractor={(item) => item.notification_id}
showsVerticalScrollIndicator={false}
refreshControl={
  <RefreshControl
      refreshing={refreshing}
      onRefresh={onRefresh}
      colors={['#449282', '#449282']}
  />
}
renderItem={({item}) => (
  <View style={styles.tile}>
  <NotificationTile item={item} />
  </View>
)}
 />
<HeightSpacer height={"10%"}/>
   </SafeAreaView>
  )
}

export default Notifications