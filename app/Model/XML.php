<?php

namespace app\Model;


class XML {

    protected $simpleXMLElement;

    protected $lastRequestedFile;

    protected $availableDaysIn = [];

    protected $availableHoursIn = [];

    protected $availableDoctors = [];

    protected $insertIntoSettings = [];

    protected $reservedContent = [];

    protected $packagesDir;


    public function __construct()
    {

        $this->packagesDir = __DIR__."/../../packages/";

        if (!file_exists($this->packagesDir."reserved.xml"))
        {
            $this->reservedContent = false;
        } else {
            $this->reservedContent = $this->toSimpleXML("reserved.xml")
                ->search("reserved");
        }
    }

    public function toSimpleXML($DIR)
    {
        $dir = $this->packagesDir.$DIR;

        if (file_exists($dir))
        {
            $dir = $this->packagesDir.$DIR;
        } else {
            $dir = __DIR__."/emergency.xml";
        }

        $content = file_get_contents($dir);
        $this->simpleXMLElement = new \SimpleXMLElement($content);

        $this->lastRequestedFile = $DIR;

        return $this;
    }

    public function search($SEARCH_IN, $SEARCH_WHERE = null, $jsonOutput = false)
    {
        $search = $this->simpleXMLElement
            ->xpath("//$SEARCH_IN$SEARCH_WHERE");

        return $jsonOutput === false ? $search : json_encode($search);
    }

    /*
     *  GET AVAILABLE DAYS IN [SECTION START]
     */

    public function getAvailableDaysIn($CONTENT, $YEAR, $MONTH, $DOC)
    {

        foreach($CONTENT as $row)
        {
            $date = $row->Termin;
            $doc  = $row->IDUsers;

            if ( $DOC == "*" && $this->getYear($date) === $YEAR && $this->getMonth($date) == $MONTH )
            {
                $this->setAvailableDaysIn($date);

            } elseif ( $doc == $DOC && $this->getYear($date) === $YEAR && $this->getMonth($date) == $MONTH )
            {
                $this->setAvailableDaysIn($date);
            }
        }
        return json_encode($this->availableDaysIn);
    }

    protected function setAvailableDaysIn($date)
    {
        $date = (string) $date;

        if (!in_array($date, $this->availableDaysIn))
        {
            array_push($this->availableDaysIn, $date);
        }
    }

    /*
     *  GET AVAILABLE HOURS IN [SECTION START]
     */

    public function getAvailableHoursIn($CONTENT, $DATE, $DOC)
    {
        $DATE = (string) $DATE;

        foreach($CONTENT as $row)
        {
            $date = $row->Termin;
            $hour = $row->Godzina;
            $doc  = $row->IDUsers;
            $nfz  = (string) $row->NFZ === "NFZ" ? "NFZ" : "PRYWATNA";
            $shop = $row->IDSalon;
            $cc   = $row->cc;

            if ($this->isAvailable($date." ".$hour))
            {
                if ( $DOC == "*" && $date == $DATE )
                {
                    array_push($this->availableHoursIn, [

                        "hour" => $hour,
                        "doc"  => $doc,
                        "nfz"  => $nfz,
                        "date" => $date,
                        "shop" => $shop,
                        "cc"   => $cc

                    ]);
                } elseif( $doc == $DOC && $date == $DATE )
                {
                    array_push($this->availableHoursIn, [

                        "hour" => $hour,
                        "doc"  => $doc,
                        "nfz"  => $nfz,
                        "date" => $date,
                        "shop" => $shop,
                        "cc"   => $cc

                    ]);
                }
            }

        }
        return json_encode($this->availableHoursIn);
    }

    public function isAvailable($date = false)
    {

        if ($this->reservedContent === false) {
            return true;
        }

        $now = date('Y-m-d')." ".date('H:i:s');

        foreach($this->reservedContent as $row)
        {
            $visit_date = (string) $row->visit_date;

            if ($visit_date == $date && $now < $row->reserved_to )
            {
                return false;
            }

        }

        return true; // If no reservation for that hour

    }

    /*
     *  GET AVAILABLE DOCTORS [SECTION START]
     */

    public function getAvailableDoctors($CONTENT)
    {
        foreach($CONTENT as $row)
        {
            $this->setAvailableDoctors($row->IDUsers, $row->Specjalista);
        }
        return json_encode($this->availableDoctors);
    }

    protected function setAvailableDoctors($id, $name)
    {
        $id = (string) $id;
        $name = (string) $name;

        $fullName = explode(",", $name)[0];
        $degree   = explode(",", $name)[1];

        if (!in_array(["id" => $id, "name" => $fullName, "degree" => $degree], $this->availableDoctors))
        {
            array_push($this->availableDoctors, ["id" => $id, "name" => $fullName, "degree" => $degree]);
        }
    }

    /*
     *  GET FIRST NFZ DATE [SECTION START]
     */

    public function getFirstNFZ($CONTENT, $ID)
    {
        foreach($CONTENT as $row)
        {
            $doc  = (int) $row->IDUsers;
            $date = $row->Termin;
            $nfz  = (string) $row->NFZ;

            if ( $nfz === "NFZ" && $ID === "*" )
            {
                return $date;

            } elseif ( $nfz === "NFZ" && $ID == $doc )
            {
                return $date;
            }
        }
    }

    protected function getYear($date)
    {
        $date = explode("-", $date);

        return $date[0];
    }

    protected function getMonth($date)
    {
        $date = explode("-", $date);

        return $date[1];
    }

    /*
     *  Insert Into [SECTION START]
     */

    public function insertInto($FILE, $PARENT)
    {
        $this->insertIntoSettings = [$FILE, $PARENT];

        return $this;
    }

    public function values($values = array())
    {

        $dir = $this->packagesDir.$this->insertIntoSettings[0];

        if (file_exists($dir))
        {
            $xml = file_get_contents($dir);
            $xml = new \SimpleXMLElement($xml);

        } else {
            $xml = new \SimpleXMLElement('<NewDataSet/>');
        }

        $parent = $xml->addChild($this->insertIntoSettings[1]);

        foreach($values as $name => $value)
        {
            $parent->addChild($name, $value);
        }

        Header('Content-type: text/xml');
        $xml->asXML($dir);

    }

}