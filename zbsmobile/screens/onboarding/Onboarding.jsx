import { StyleSheet,StatusBar, FlatList } from "react-native";
import React from 'react';
import { SafeAreaView } from "react-native-safe-area-context";
import Slides from "../../components/Onboard/Slides";

const Onboarding = () => {
    const slides = [
        {
            id:1,
            image: require('../../assets/images/zbshome1.jpg'),
            title: "Welcome to ZBS App"
        }
      
    ];
    return (
        <>
        <StatusBar barStyle="light-content" backgroundColor="#449282" />
       <FlatList 
       pagingEnabled
       horizontal
       showsHorizontalScrollIndicator={false}
       data = {slides}
       keyExtractor={(item) => item.id}
       renderItem={({item}) => <Slides item={item}/>}
       />
       </>
    )
}

export default Onboarding;