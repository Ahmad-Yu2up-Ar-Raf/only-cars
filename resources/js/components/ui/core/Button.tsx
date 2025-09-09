


import { useState } from "react"
import { Button } from "../fragments/button"

export default function Buttoasdn() {
    const hH : string = "as"


   const [number, setNumber] = useState(2131)


    return (
        <>
<Button 
onClick={() => setNumber(number + 1)}
>
    Hehsydg
</Button>
<h1>

{number}
</h1>
        </>
    )
}