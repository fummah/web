import { StyleSheet, View,Dimensions } from 'react-native'
import React from 'react'
import { COLORS} from '../../constants/theme'
import {
    LineChart
  } from "react-native-chart-kit";
  
const HomeChart = ({graph1,graph2}) => {

  return (
    <View>
    <View>
   
  <LineChart
    data={{
      labels: graph1,
      datasets: [
        {
          data: graph2
        }
      ]
    }}
    width={Dimensions.get("window").width} // from react-native
    height={220}
    yAxisLabel=""
    yAxisSuffix=""
    yAxisInterval={1} // optional, defaults to 1
    chartConfig={{
      backgroundColor: COLORS.green,
      backgroundGradientFrom: COLORS.green,
      backgroundGradientTo: COLORS.green,
      decimalPlaces: 0, // optional, defaults to 2dp
      color: (opacity = 1) => `rgba(255, 255, 255, ${opacity})`,
      labelColor: (opacity = 1) => `rgba(255, 255, 255, ${opacity})`,
      style: {
        borderRadius: 16
      },
      propsForDots: {
        r: "6",
        strokeWidth: "2",
        stroke: COLORS.gray
      }
    }}
    bezier
    style={{
      marginVertical: 8,
      borderRadius: 0
    }}
  />
</View>
    </View>
  )
}

export default HomeChart

const styles = StyleSheet.create({
    shadow:{
        shadowColor:"#000",
        shadowOffset:{
            width:0,
            height:4
        }
    }
})