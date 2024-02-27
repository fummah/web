import { FlatList, StyleSheet, Text, TouchableOpacity, View } from 'react-native'
import React from 'react';
import { useNavigation } from '@react-navigation/native';
import reusable from '../Reusable/reusable.style';
import ReusableText from '../Reusable/ReusableText';
import { COLORS, SIZES, TEXT } from '../../constants/theme';
import { Feather } from '@expo/vector-icons';
import ReusableTile from '../Reusable/ReusableTile';

const Latestfunerals = ({register}) => {
    const navigation = useNavigation();
    
  return (
    <View style={styles.container}>
      

<FlatList
data={register}
horizontal
keyExtractor={(item) => item.register_id}
contentContainerStyle={{columnGap:SIZES.medium}}
showsHorizontalScrollIndicator={false}
renderItem={({item}) => (
   <ReusableTile
item={item}
onPress={() => {}}
   />
)}
/>
    </View>
  )
}

export default Latestfunerals

const styles = StyleSheet.create({
    container : {
        paddingTop:12
    }
})