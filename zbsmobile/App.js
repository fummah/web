import { StatusBar } from 'expo-status-bar';
import { StyleSheet, Text, Platform } from 'react-native';
import { useFonts } from 'expo-font';
import * as Splashscreen from 'expo-splash-screen';
import { useCallback,useState,useEffect,useRef } from 'react';
import * as Device from 'expo-device';
import { NavigationContainer } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { Onboarding, Search,FuneralDetails, Register } from './screens';
import BottomTabNavigation from './navigation/BottomTabNavigation';
import AuthTopTab from './navigation/AuthTopTab';
import AsyncStorage from '@react-native-async-storage/async-storage';
import * as Notifications from 'expo-notifications';
import Constants from 'expo-constants';

Notifications.setNotificationHandler({
  handleNotification: async () => ({
    shouldShowAlert: true,
    shouldPlaySound: false,
    shouldSetBadge: false,
  }),
});

async function registerForPushNotificationsAsync() {
  let token;

  if (Platform.OS === 'android') {
    Notifications.setNotificationChannelAsync('default', {
      name: 'default',
      importance: Notifications.AndroidImportance.MAX,
      vibrationPattern: [0, 250, 250, 250],
      lightColor: '#FF231F7C',
    });
  }

  if (Device.isDevice) {
    const { status: existingStatus } = await Notifications.getPermissionsAsync();
    let finalStatus = existingStatus;
    if (existingStatus !== 'granted') {
      const { status } = await Notifications.requestPermissionsAsync();
      finalStatus = status;
    }
    if (finalStatus !== 'granted') {
      alert('Failed to get push token for push notification!');
      return;
    }
    token = await Notifications.getExpoPushTokenAsync({
      projectId: 'b0aab314-f807-4b21-b8db-88f7092b3bc6',
    });
   
    await AsyncStorage.setItem("EXPOTOKEN", token.data);
    console.log(token);
  } else {
    alert('Must use physical device for Push Notifications');
  }

  return token.data;
}


const Stack = createNativeStackNavigator();

export default function App() {
  const [home,setHome] = useState(null);
  const [expoPushToken, setExpoPushToken] = useState('');
  const [notification, setNotification] = useState(false);
  const notificationListener = useRef();
  const responseListener = useRef();
  const [fontsLoaded] = useFonts({
regular:require('./assets/fonts/regular.otf'),
medium:require('./assets/fonts/medium.otf'),
bold:require('./assets/fonts/bold.otf'),
light:require('./assets/fonts/light.otf'),
xtrabold:require('./assets/fonts/xtrabold.otf'),
  });
  useEffect(() => {
    registerForPushNotificationsAsync().then(token=> setExpoPushToken(token));   
    notificationListener.current = Notifications.addNotificationReceivedListener(notification => {
      setNotification(notification);
    });

    responseListener.current = Notifications.addNotificationResponseReceivedListener(response => {
      console.log(response);
    });

    return () => {
      Notifications.removeNotificationSubscription(notificationListener.current);
      Notifications.removeNotificationSubscription(responseListener.current);
    };
  }, []);

  const onLayoutRootView = useCallback(async () => {
    if(fontsLoaded){
      const access = await AsyncStorage.getItem('ACCEESS_GRANTED');
      setHome(access); 
      console.log(access);
      await Splashscreen.hideAsync();
    }
  }, [fontsLoaded]);

  if(!fontsLoaded)
  {
    const handleHome = async() =>{
      const access = await AsyncStorage.getItem('ACCEESS_GRANTED');
      setHome(access); 
    }
    handleHome();
    return null;
  }
  

  return (
  <NavigationContainer>
<Stack.Navigator>
  {home===null ? 
  <><Stack.Screen name='Onboard' component={Onboarding} options={{headerShown:false}}/>
  <Stack.Screen name='Bottom' component={BottomTabNavigation} options={{headerShown:false}}/>
  </>
  :
  <>
  <Stack.Screen name='Bottom' component={BottomTabNavigation} options={{headerShown:false}}/>
  <Stack.Screen name='Onboard' component={Onboarding} options={{headerShown:false}}/>
  </>
} 
  <Stack.Screen name='Search' component={Search} options={{headerShown:false}}/>
  <Stack.Screen name='FuneralDetails' component={FuneralDetails} options={{headerShown:false}}/>
  <Stack.Screen name='Register' component={Register} options={{headerShown:false}}/>
  <Stack.Screen name='Auth' component={AuthTopTab} options={{headerShown:false}}/>

</Stack.Navigator>
  </NavigationContainer>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
    alignItems: 'center',
    justifyContent: 'center',
  },
  textStyle:{
    fontFamily:"xtrabold",
    fontSize:18
  }
});
