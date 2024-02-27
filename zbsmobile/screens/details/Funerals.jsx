import { StyleSheet, Text, View } from 'react-native'
import React from 'react'
import { SafeAreaView } from 'react-native-safe-area-context'
import AppBar from '../../components/Reusable/AppBar'
import { COLORS } from '../../constants/theme'

const Funerals = () => {
  return (
    <SafeAreaView style={{marginHorizontal:20}}>
        <View style={{height:80}}>
            <AppBar title={'Funerals'} color={COLORS.white}  icon={'search1'} color1={COLORS.white}/>
        </View>

        <View style={{paddingTop:20}}>
            <Text>Funerals</Text>
        </View>
    </SafeAreaView>
  )
}

export default Funerals

const styles = StyleSheet.create({})