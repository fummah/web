import { StyleSheet } from "react-native";
import { COLORS } from "../../constants/theme";

const styles = StyleSheet.create({
box:{
    backgroundColor: COLORS.white,
    width:40,
    height:40,
    borderRadius:12,
    alignItems:"center",
    justifyContent:"center"
},
shadow:{
    shadowColor:"#000",
    shadowOffset:{
        width:0,
        height:4
    }
},
container:{
    flex:1,
    justifyContent:"center",
    alignItems:"center"
}
});

export default styles;