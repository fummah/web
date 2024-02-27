import { StyleSheet,Text, FlatList } from "react-native";
import React from 'react';
import { SafeAreaView } from "react-native-safe-area-context";
import Slides from "../../components/Onboard/Slides";

const Onboarding = () => {
    const slides = [
        {
            id:1,
            image: require('../../assets/images/zbshome1.jpg'),
            title: "Welcome to ZBS App"
        },
        {
            id:2,
            image: require('../../assets/images/zbshome2.jpg'),
            title: "Get yourself covered"
        }
        ,
        {
            id:3,
            image: require('../../assets/images/zbshome3.jpg'),
            title: "Get onboard with others"
        }
      
    ];
    return (
       <FlatList 
       pagingEnabled
       horizontal
       showsHorizontalScrollIndicator={false}
       data = {slides}
       keyExtractor={(item) => item.id}
       renderItem={({item}) => <Slides item={item}/>}
       />
    )
}

export default Onboarding;