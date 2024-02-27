import { StyleSheet, Text, TouchableOpacity, View } from 'react-native'
import React from 'react'
import reusable from './reusable.style'
import { COLORS, SIZES } from '../../constants/theme'
import {HeightSpacer, Mark, NetworkImage, ReusableText, WidthSpacer} from '../../components'
import { useNavigation } from '@react-navigation/native'

const ReusableTile = ({item}) => {
    const navigation = useNavigation();
    const goDetails = (funeral_id) =>{
        navigation.navigate('FuneralDetails',{funeral_id:funeral_id});
            }
  
  return (
   <TouchableOpacity style={styles.container} onPress={goDetails.bind(null, item.funeral_id)}>
    <View style={reusable.rowWidthSpace('flex-start')}>
<NetworkImage source="https://zbsburial.com/images/1.jpg" width={80} height={80} radius={12}/>
<WidthSpacer width={15}/>

<View>
<ReusableText
text={item.funeral_name}
family={'medium'}
size={SIZES.medium}
color={COLORS.black}
/>

<HeightSpacer height={8}/>

<ReusableText
text={`Group ${item.group_name}`}
family={'medium'}
size={14}
color={COLORS.gray}
/>

<HeightSpacer height={8}/>

<View style={reusable.rowWidthSpace('flex-start')}>

<Mark status={item.status}/>

<WidthSpacer width={5}/>

<ReusableText
text={` (R${item.amount_paid})`}
family={'medium'}
size={14}
color={COLORS.gray}
/>
</View>
</View>
    </View>
   </TouchableOpacity>
  )
}

export default ReusableTile

const styles = StyleSheet.create({
    container:{
        padding:10,
        backgroundColor:COLORS.lightWhite,
        borderRadius:12
    }
})