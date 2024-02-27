import { View, Image } from "react-native";
import React from 'react';
import styles from "./slides.style";
import {ReusableBtn, ReusableText, HeightSpacer} from "../../components/index";
import { COLORS, SIZES } from "../../constants/theme";
import { useNavigation } from "@react-navigation/native";
import AsyncStorage from '@react-native-async-storage/async-storage';

const Slides = ({item}) => {
const navigation = useNavigation();

const handleLogin = async() =>{
    await AsyncStorage.removeItem("ACCEESS_GRANTED");
    navigation.navigate('Auth', {
        screen: 'Signin',
      });
}

const handleRegister = async() =>{
    await AsyncStorage.removeItem("ACCEESS_GRANTED");
    navigation.navigate('Auth', {
        screen: 'Registration',
      });
}

    return (
       <View>
        <Image source={item.image} style={styles.image}/>
        <View style={styles.stack}>
<ReusableText
text={item.title}
family={'medium'}
size={SIZES.xxLarge}
color={COLORS.white }
/>
<ReusableText
text={"Get Yourself Covered"}
family={'medium'}
size={SIZES.medium}
color={COLORS.white }
/>
<HeightSpacer height={30}/>


<ReusableBtn 
onPress={handleLogin}
btnText={"Get Started"}
width={(SIZES.width-50)/2.2}
backgroundColor={COLORS.green}
borderColor={COLORS.green}
textColor={COLORS.white}
borderWidth={0}
icon={"rocket"}
radius={50}
/>

<HeightSpacer height={15}/>

<ReusableBtn 
onPress={handleRegister}
btnText={"Register Now"}
width={(SIZES.width-50)/2.2}
backgroundColor={COLORS.white}
borderColor={COLORS.white}
textColor={COLORS.green}
borderWidth={0}
icon={"user"}
radius={50}
/>
        </View>
       </View>
    )
}

export default Slides;