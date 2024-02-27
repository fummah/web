import { StyleSheet, Text, View, VirtualizedList } from 'react-native'
import React from 'react'
import HeightSpacer from '../Reusable/HeightSpacer';
import { COLORS, SIZES, TEXT } from '../../constants/theme';
import Country from '../Tiles/Country/Country';
import ReusableText from '../Reusable/ReusableText';
import { Feather } from '@expo/vector-icons';

const Places = ({locations}) => {
    
  return (
    <View>
     
 
<HeightSpacer height={20}/>
      <VirtualizedList
data={locations}
horizontal
keyExtractor={(item) => item.location_id}
showsHorizontalScrollIndicator={false}
getItemCount={(data) => data.length}
getItem={(data,index) => data[index]}
renderItem={({item,index}) => (
<View style={{marginRight:SIZES.medium}}>
<Country item={item}/>
</View>
)}
      />

      
    </View>
  );
};

export default Places

const styles = StyleSheet.create({})