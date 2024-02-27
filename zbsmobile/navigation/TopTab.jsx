import { View, Text, Image } from 'react-native'
import React from 'react';
import { Admins, Dependencies, Details } from '../screens';
import {createMaterialTopTabNavigator} from '@react-navigation/material-top-tabs';
import { COLORS } from '../constants/theme';
import { HeightSpacer, NetworkImage } from '../components';
import AppBar from '../components/Reusable/AppBar';
import styles from './toTab.style';
import { SafeAreaView } from 'react-native-safe-area-context';
import reusable from '../components/Reusable/reusable.style';

const Tab = createMaterialTopTabNavigator();

const TopTab = () => {
  return (

  
    <View style={{flex:1}}>
        <SafeAreaView>
        <View style={{backgroundColor:COLORS.lightWhite}}>
            <View>
               
                <NetworkImage
source={"https://zbsburial.com/images/4.png"}
width={'100%'}
height={100}
radius={0}
                />
             
           
            </View>

        </View>
        </SafeAreaView>
      <Tab.Navigator
      screenOptions={{
        tabBarActiveTintColor: COLORS.green,
        tabBarIndicatorStyle: { backgroundColor: COLORS.green },
      }}
      >
      <Tab.Screen name='Dependents' component={Dependencies}/>
        <Tab.Screen name='Admins' component={Admins}/>
        <Tab.Screen name='Info' component={Details}/>       
      </Tab.Navigator>
    </View>
   
  )
}

export default TopTab