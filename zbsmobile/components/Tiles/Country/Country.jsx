import { StyleSheet, Text, TouchableOpacity, View } from 'react-native'
import React from 'react';
import { TEXT,COLORS } from '../../../constants/theme';
import {HeightSpacer, NetworkImage,ReusableText} from '../../../components/index';
import { useNavigation } from '@react-navigation/native';

const Country = ({item}) => {
  const navigation = useNavigation();
  return (
   <TouchableOpacity onPress={() => navigation.navigate('CountryDetails',item)}>
    <View>

        <NetworkImage source="https://zbsburial.com/images/2.png" width={85} height={85} radius={12}
        /> 
        <HeightSpacer height={3}/>  
    <ReusableText
text={item.location_name}
family={'medium'}
size={TEXT.medium}
color={COLORS.black }
align={"center"}
/>
    </View>
   </TouchableOpacity>
  )
}

export default Country

const styles = StyleSheet.create({})