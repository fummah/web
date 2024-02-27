import { ScrollView, StyleSheet, Text, View } from 'react-native'
import React from 'react'
import {createMaterialTopTabNavigator} from '@react-navigation/material-top-tabs';
import { Registration, Signin } from '../screens';
import { COLORS } from '../constants/theme';
import { NetworkImage } from '../components';

const Tab = createMaterialTopTabNavigator();
const AuthTopTab = () => {
  return (
    <View style={{flex:1,backgroundColor:COLORS.lightWhite}}>
        <ScrollView style={{flex:1,backgroundColor:COLORS.lightWhite}}>
        
            <NetworkImage
source={"https://zbsburial.com/images/3.png"}
width={'100%'}
height={150}
radius={0}
                />
        <View style={{height:600}}>
    <Tab.Navigator
     screenOptions={{
      tabBarActiveTintColor: COLORS.green,
      tabBarIndicatorStyle: { backgroundColor: COLORS.green },
    }}
    >
               <Tab.Screen name='Signin' component={Signin}/>
        <Tab.Screen name='Registration' component={Registration}/>        
    </Tab.Navigator>
    </View>
    </ScrollView>
    </View>
  )
}

export default AuthTopTab

const styles = StyleSheet.create({})