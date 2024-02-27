import { View, Text, TouchableOpacity } from 'react-native'
import React from 'react';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { Home, Chat, Profile,Location, Search, Details, Notifications, AccountStatement } from '../screens';
import { Ionicons } from '@expo/vector-icons';
import { COLORS } from '../constants/theme';
import TopTab from './TopTab';
import AuthTopTab from './AuthTopTab';

const Tab = createBottomTabNavigator();


const tabBarStyle = {
    position:"absolute",
    bottom:0,
    right:0,
    left:0,
    elevation:0,
    backgroundColor:COLORS.white,
    borderTopColor:"transparent",
    height:65
};

const TabBarCustomButton = ({children,onPress}) => (
    <TouchableOpacity
    style={{
        top:-30,
        justifyContent:"center",
        alignItems:"center"
    }}
    onPress={onPress}
    >
<View
colors={[COLORS.green,COLORS.blue]}
style={
    {
        width:50,
        height:50,
        borderRadius:35,
        backgroundColor:COLORS.green
    }
}
>
    {children}
</View>
    </TouchableOpacity>
)

const BottomTabNavigation = () => {
  return (
    <Tab.Navigator
    initialRouteName="Home"
    activeColor="#EB6A58"
    tabBarHideKeyBoard={true}
    headerShown={false}
    inactiveColor="#3e2465"
    barStyle={{paddingBottom:48}}
    >
        <Tab.Screen
        name="Home" 
        component={Home} 
        options={{
            tabBarStyle:tabBarStyle,
            tabBarShowLabel: false,
            headerShown:false,
            tabBarIcon:({focused}) => (
<Ionicons 
name={focused ? "home" : "home-outline"}
color={focused ? COLORS.green : COLORS.green}
size={26}
/>
  )
            }}
        /> 



<Tab.Screen
name='Accounts' component={AccountStatement} options={
    {
        tabBarStyle:tabBarStyle,
    tabBarShowLabel: false,
    headerShown:false,
    tabBarIcon:({focused}) => (
<Ionicons 
name={focused?"cash":"cash-outline"}
color={focused?COLORS.green:COLORS.green}
size={26}
/>
    )
    }}
/>
<Tab.Screen
        name='Search' component={Search} options={
            {
                tabBarStyle:tabBarStyle,
            tabBarShowLabel: false,
            headerShown:false,
            tabBarIcon:({focused}) => (
<Ionicons 
name={focused?"search":"search-outline"}
color={focused?COLORS.lightWhite:COLORS.white}
size={26}
/>
            ),
            tabBarButton:(props) =>(
                <TabBarCustomButton
                {...props}
                />
            )
            }}
        />
         <Tab.Screen
        name='Chat' component={Notifications} options={
            {
                tabBarStyle:tabBarStyle,
            tabBarShowLabel: false,
            headerShown:false,
            tabBarIcon:({focused}) => (
<Ionicons 
name={focused?"chatbubble-ellipses":"chatbubble-ellipses-outline"}
color={focused?COLORS.green:COLORS.green}
size={26}
/>
            )
            }}
        />
         
         <Tab.Screen
        name='Profile' component={TopTab} options={
            {
                tabBarStyle:tabBarStyle,
            tabBarShowLabel: false,
            headerShown:false,
            tabBarIcon:({focused}) => (
<Ionicons 
name={focused? "person" : "person-outline"}
color={focused?COLORS.green:COLORS.green}
size={26}
/>
            )
            }}
        />
    </Tab.Navigator>
  );
};

export default BottomTabNavigation