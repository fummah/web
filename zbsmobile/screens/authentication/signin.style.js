import { StyleSheet } from "react-native";
import { COLORS, SIZES } from "../../constants/theme";

const styles = StyleSheet.create({
container:{
    flex:1,
    padding:20,
    backgroundColor:COLORS.lightWhite
},
inputWrapper: (borderColor)=>({
    borderColor:borderColor,
    backgroundColor:COLORS.lightWhite,
    borderWidth:1,
    height:50,
    borderRadius:12,
    flexDirection:"row",
    paddingHorizontal:15,
    alignItems:"center"
}),
wrapper:{
    marginBottom:20
},
label:{
    fontFamily:"regular",
    marginBottom:5,
    fontSize:SIZES.small,
    marginEnd:5,
    textAlign:"right",
    color:COLORS.green
},
errorMessage:{
    color:COLORS.red,
    fontSize:SIZES.small,
    fontFamily:'regular',
    marginTop:5,
    marginLeft:5,
},
container1:{
    padding:20,
    backgroundColor:COLORS.lightWhite,
    borderRadius:12,
   
}
});

export default styles;